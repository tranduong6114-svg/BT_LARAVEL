<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTime;

class EmployeeService {
    private array $departmentMap = [];
    private array $positionMap = [];

    public function __construct() {
        $this->loadDepartmentMap();
        $this->loadPositionMap();
    }

    private function loadDepartmentMap(): void {
        foreach(Department::all() as $dept) {
            $key = mb_strtolower(trim($dept->name), 'UTF-8');
            $this->departmentMap[$key] = $dept->id;
        }
    }

    private function loadPositionMap(): void {
        foreach(Position::all() as $pos) {
            $key = mb_strtolower(trim($pos->name), 'UTF-8');
            $this->positionMap[$key] = $pos->id;
        }
    }

    public function importFromPath(string $path): array {
        $result = [
            'success'  => true,
            'imported' => 0,
            'updated'  => 0,
            'errors'   => []
        ];

        if (!file_exists($path)) {
            $result['success'] = false;
            $result['errors'][] = "Không tìm thấy file: {$path}";
            return $result;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $result['success'] = false;
            $result['errors'][] = "Không thể mở file CSV.";
            return $result;
        }

        $headers = fgetcsv($handle);
        if ($headers === false || count($headers) === 0) {
            $result['success'] = false;
            $result['errors'][] = 'File CSV rỗng.';
            fclose($handle);
            return $result;
        }

        $headers = array_map('trim', $headers);

        $expectedColumn = [
            'Mã nhân viên', 'Họ tên', 'email',
            'Lương cơ bản', 'Lương', 'Sinh nhật',
            'Phòng ban', 'Chức vụ'
        ];

        foreach ($expectedColumn as $col) {
            if (!in_array($col, $headers, true)) {
                $result['success'] = false;
                $result['errors'][] = "File CSV thiếu cột bắt buộc: {$col}";
                fclose($handle);
                return $result;
            }
        }

        $colIndex = array_flip($headers);
        $allRows = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($headers)) {
                $result['success'] = false;
                $result['errors'][] = "Dòng " . (count($allRows) + 2) . ": số cột không khớp.";
                fclose($handle);
                return $result;
            }

            $allRows[] = [
                'emp_id'          => trim($row[$colIndex['Mã nhân viên']] ?? ''),
                'full_name'       => trim($row[$colIndex['Họ tên']] ?? ''),
                'email'           => trim($row[$colIndex['email']] ?? ''),
                'base_salary'     => trim($row[$colIndex['Lương cơ bản']] ?? ''),
                'actual_salary'   => trim($row[$colIndex['Lương']] ?? ''),
                'birthday'        => trim($row[$colIndex['Sinh nhật']] ?? ''),
                'department_name' => trim($row[$colIndex['Phòng ban']] ?? ''),
                'position_name'   => trim($row[$colIndex['Chức vụ']] ?? '')
            ];
        }

        fclose($handle);

        if (empty($allRows)) {
            $result['success'] = false;
            $result['errors'][] = "Không có dòng dữ liệu để import.";
            return $result;
        }

        DB::beginTransaction();
        try {
            foreach ($allRows as $index => $data) {
                $rowNumber = $index + 2;

                $errors = $this->validateRow($data, $rowNumber);
                if (!empty($errors)) {
                    $result['success'] = false;
                    $result['errors'] = array_merge($result['errors'], $errors);
                    $result['imported'] = 0;
                    $result['updated']  = 0;
                    DB::rollback();
                    return $result;
                }

                $exists = Employee::where('emp_id', $data['emp_id'])->exists();

                $deptKey = mb_strtolower(trim($data['department_name']), 'UTF-8');
                $posKey  = mb_strtolower(trim($data['position_name']), 'UTF-8');

                $employeeData = [
                    'full_name'     => $data['full_name'],
                    'email'         => $data['email'],
                    'base_salary'   => (float) $data['base_salary'],
                    'actual_salary' => (float) $data['actual_salary'],
                    'birthday'      => $data['birthday'],
                    'department_id' => $this->departmentMap[$deptKey],
                    'position_id'   => $this->positionMap[$posKey]
                ];

                if ($exists) {
                    Employee::where('emp_id', $data['emp_id'])->update($employeeData);
                    $result['updated']++;
                } else {
                    $employeeData['emp_id'] = $data['emp_id'];
                    Employee::create($employeeData);
                    $result['imported']++;
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $result['success'] = false;
            $result['imported'] = 0;
            $result['updated']  = 0;
            $result['errors'] = ['Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
        }

        return $result;
    }

    private function validateRow(array $data, int $rowNumber): array {
        $errors = [];

        if (empty($data['emp_id'])) {
            $errors[] = "Dòng {$rowNumber}: Mã nhân viên trống.";
            return $errors;
        }

        if (mb_strlen($data['full_name'], 'UTF-8') > 255) {
            $errors[] = "Dòng {$rowNumber}: Họ tên vượt quá 255 ký tự.";
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Dòng {$rowNumber}: Email không đúng định dạng.";
        }

        $baseSalary   = (float) $data['base_salary'];
        $actualSalary = (float) $data['actual_salary'];
        if ($baseSalary > $actualSalary) {
            $errors[] = "Dòng {$rowNumber}: Lương cơ bản lớn hơn lương thực nhận.";
        }

        $date = DateTime::createFromFormat('Y-m-d', $data['birthday']);
        if (!$date || $date->format('Y-m-d') !== $data['birthday']) {
            $errors[] = "Dòng {$rowNumber}: Ngày sinh không đúng định dạng yyyy-mm-dd.";
        }

        $deptKey = mb_strtolower(trim($data['department_name']), 'UTF-8');
        if (!isset($this->departmentMap[$deptKey])) {
            $errors[] = "Dòng {$rowNumber}: Phòng ban '{$data['department_name']}' không tồn tại trong hệ thống.";
        }

        $posKey = mb_strtolower(trim($data['position_name']), 'UTF-8');
        if (!isset($this->positionMap[$posKey])) {
            $errors[] = "Dòng {$rowNumber}: Chức vụ '{$data['position_name']}' không tồn tại trong hệ thống.";
        }

        return $errors;
    }

    public function getAvgSalaryUnder30(): array {
        $employees = Employee::all();
        $currentYear = (int) date('Y');
        $under30 = $employees->filter(function ($emp) use ($currentYear) {
            $birthYear = (int) date('Y', strtotime($emp->birthday));
            return ($currentYear - $birthYear) < 30;
        });
        if ($under30->isEmpty()) {
            return [
                'success'        => true,
                'average_salary' => 0,
                'count'          => 0,
                'message'        => 'Không có nhân viên nào dưới 30 tuổi.'
            ];
        }
        $avg = $under30->avg('actual_salary');
        return [
            'success'        => true,
            'average_salary' => round($avg),
            'count'          => $under30->count(),
            'message'        => "Lương trung bình nhân viên dưới 30 tuổi: " . number_format(round($avg)) . " VNĐ."
        ];
    }
}
<?php

namespace App\Services;

use App\Models\Employee;

class InsuranceService {
    private const BHXH_RATE = 0.08;
    private const BHYT_RATE = 0.015;
    private const BHTN_RATE = 0.01;

    private const CSV_PATH = 'export/output_bhxh.csv';

    public function calculateAndExport(): array {
        $result = [
            'success'       => true,
            'message'       => '',
            'file_path'     => '',
            'total_records' => 0
        ];

        $employees = Employee::with(['department', 'position'])->get();

        if ($employees->isEmpty()) {
            $result['success'] = false;
            $result['message'] = 'Không có nhân viên nào trong hệ thống.';
            return $result;
        }

        $result['total_records'] = $employees->count();

        $fullPath = storage_path('app/' . self::CSV_PATH);
        $dir = dirname($fullPath);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $handle = fopen($fullPath, 'w');
        if ($handle === false) {
            $result['success'] = false;
            $result['message'] = 'Không thể mở file CSV để ghi.';
            return $result;
        }

        fputcsv($handle, ['Mã nhân viên', 'Họ tên', 'Lương cơ bản', 'Tổng BHXH']);

        foreach ($employees as $emp) {
            $totalInsurance = $emp->base_salary * (self::BHXH_RATE + self::BHYT_RATE + self::BHTN_RATE);

            fputcsv($handle, [
                $emp->emp_id,
                $emp->full_name,
                number_format($emp->base_salary, 0, '', ''),
                number_format($totalInsurance, 0, '', '')
            ]);
        }

        fclose($handle);

        $result['file_path'] = 'storage/app/' . self::CSV_PATH;
        $result['message']   = "Xuất BHXH thành công: {$result['total_records']} nhân viên.";
        return $result;
    }
}
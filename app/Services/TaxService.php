<?php

namespace App\Services;

use App\Models\Employee;

class TaxService
{
    private const DEDUCTION = 11000000;

    private const CSV_FULL = 'export/output_tax.csv';
    private const CSV_TOP3 = 'export/output_tax_top3.csv';

    public function calculate(float $actualSalary): float
    {
        $taxableIncome = $actualSalary - self::DEDUCTION;

        if ($taxableIncome <= 0) {
            return 0;
        }

        if ($taxableIncome <= 5000000) {
            return $taxableIncome * 0.05;
        }

        if ($taxableIncome <= 10000000) {
            return $taxableIncome * 0.10;
        }

        return $taxableIncome * 0.15;
    }

    public function exportFull(): array
    {
        return $this->export('full');
    }

    public function exportTop3(): array
    {
        return $this->export('top3');
    }

    private function export(string $mode): array
    {
        $result = [
            'success'       => true,
            'message'       => '',
            'file_path'     => '',
            'total_records' => 0,
        ];

        $employees = Employee::all();

        if ($employees->isEmpty()) {
            $result['success'] = false;
            $result['message'] = 'Không có nhân viên nào trong hệ thống.';
            return $result;
        }

        $rows = $employees->map(function ($emp) {
            return [
                'emp_id'        => $emp->emp_id,
                'full_name'     => $emp->full_name,
                'actual_salary' => (float) $emp->actual_salary,
                'tax'           => $this->calculate($emp->actual_salary),
            ];
        });

        if ($mode === 'top3') {
            $rows = $rows->sortByDesc('tax')->take(3)->values();
        }

        $csvPath = $mode === 'top3' ? self::CSV_TOP3 : self::CSV_FULL;
        $fullPath = storage_path('app/' . $csvPath);
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

        fputcsv($handle, ['Mã nhân viên', 'Họ tên', 'Lương', 'Thuế phải đóng']);

        foreach ($rows as $row) {
            fputcsv($handle, [
                $row['emp_id'],
                $row['full_name'],
                number_format($row['actual_salary'], 0, '', ''),
                number_format($row['tax'], 0, '', ''),
            ]);
        }

        fclose($handle);

        $result['total_records'] = $rows->count();
        $result['file_path']     = 'storage/app/' . $csvPath;
        $result['message']       = "Xuất thuế thành công: {$result['total_records']} nhân viên.";
        return $result;
    }
}
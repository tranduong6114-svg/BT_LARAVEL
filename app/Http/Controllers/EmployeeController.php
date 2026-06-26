<?php

namespace App\Http\Controllers;

use App\Services\EmployeeService;
use App\Services\InsuranceService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;
    protected InsuranceService $insuranceService;

    public function __construct(EmployeeService $employeeService, InsuranceService $insuranceService)
    {
        $this->employeeService = $employeeService;
        $this->insuranceService = $insuranceService;
    }

    public function importCsv(): JsonResponse
    {
        $filePath = storage_path('app/import/nhanvien.csv');
        $result = $this->employeeService->importFromPath($filePath);
        return response()->json($result, $result['success'] ? 200 : 400, [], JSON_UNESCAPED_UNICODE);
    }

    public function exportBhxh(): JsonResponse
    {
        $result = $this->insuranceService->calculateAndExport();
        return response()->json($result, $result['success'] ? 200 : 400, [], JSON_UNESCAPED_UNICODE);
    }
}
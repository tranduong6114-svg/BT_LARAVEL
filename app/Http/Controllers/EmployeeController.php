<?php

namespace App\Http\Controllers;

use App\Services\EmployeeService;
use App\Services\InsuranceService;
use App\Services\TaxService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;
    protected InsuranceService $insuranceService;
    protected TaxService $taxService;

    public function __construct(EmployeeService $employeeService, InsuranceService $insuranceService, TaxService $taxService)
    {
        $this->employeeService = $employeeService;
        $this->insuranceService = $insuranceService;
        $this->taxService       = $taxService;
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

    public function exportTax(): JsonResponse
    {
        $result = $this->taxService->exportFull();
        return response()->json($result, $result['success'] ? 200 : 400, [], JSON_UNESCAPED_UNICODE);
    }
    
    public function exportTaxTop3(): JsonResponse
    {
        $result = $this->taxService->exportTop3();
        return response()->json($result, $result['success'] ? 200 : 400, [], JSON_UNESCAPED_UNICODE);
    }
}
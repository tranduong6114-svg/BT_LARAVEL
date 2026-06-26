<?php

namespace App\Http\Controllers;

use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function importCsv(): JsonResponse
    {
        $filePath = storage_path('app/import/nhanvien.csv');
        $result = $this->employeeService->importFromPath($filePath);
        return response()->json($result, $result['success'] ? 200 : 400, [], JSON_UNESCAPED_UNICODE);
    }
}
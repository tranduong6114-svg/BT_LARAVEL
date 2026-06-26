<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/import-csv', [EmployeeController::class, 'importCsv'])
    ->name('employees.importCsv');

Route::get('/export-bhxh', [EmployeeController::class, 'exportBhxh'])
    ->name('employees.exportBhxh');

Route::get('/export-tax', [EmployeeController::class, 'exportTax'])
    ->name('employees.exportTax');
    
Route::get('/export-tax-top3', [EmployeeController::class, 'exportTaxTop3'])
    ->name('employees.exportTaxTop3');
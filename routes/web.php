<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/import-csv', [EmployeeController::class, 'importCsv'])
    ->name('employees.importCsv');

Route::get('/export-bhxh', [EmployeeController::class, 'exportBhxh'])
    ->name('employees.exportBhxh');
<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/import-csv', [EmployeeController::class, 'importCsv'])
    ->name('employees.importCsv');
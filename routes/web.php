<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeCrudController;
use App\Http\Controllers\Auth\LoginController;           
use App\Http\Controllers\Auth\RegisterController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $totalEmployees = \App\Models\Employee::count();
    $employees = \App\Models\Employee::with(['department', 'position'])->get();
    $statistics = [
        'total_employees' => $totalEmployees,
        'total_departments' => \App\Models\Department::count(),
        'total_positions' => \App\Models\Position::count(),
        'avg_salary' => round(\App\Models\Employee::avg('actual_salary') ?? 0),
        'under_30_count' => \App\Models\Employee::whereRaw('TIMESTAMPDIFF(YEAR, birthday, CURDATE()) < 30')->count(),
    ];
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    return view('pages.home', compact('employees', 'statistics', 'isAdmin', 'totalEmployees'));
})->name('home')->middleware('auth');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/statistics', fn() => view('pages.statistics'))->name('employees.statistics');
    
    Route::get('/api/employees', [EmployeeCrudController::class, 'indexApi']);
    
    Route::middleware('role:admin')->group(function () {
        Route::get('/import-csv', [EmployeeController::class, 'importCsv'])->name('employees.importCsv');
        Route::get('/export-bhxh', [EmployeeController::class, 'exportBhxh'])->name('employees.exportBhxh');
        Route::get('/export-tax', [EmployeeController::class, 'exportTax'])->name('employees.exportTax');
        Route::get('/export-tax-top3', [EmployeeController::class, 'exportTaxTop3'])->name('employees.exportTaxTop3');
        Route::get('/salary-under30', [EmployeeController::class, 'getAvgSalaryUnder30'])->name('employees.salaryUnder30');
        Route::get('/export-managers', [EmployeeController::class, 'exportManagers'])->name('employees.exportManagers');
        
        Route::get('/download-bhxh', fn() => response()->download(storage_path('app/export/output_bhxh.csv')))->name('employees.downloadBhxh');
        Route::get('/download-tax', fn() => response()->download(storage_path('app/export/output_tax.csv')))->name('employees.downloadTax');
        Route::get('/download-tax-top3', fn() => response()->download(storage_path('app/export/output_tax_top3.csv')))->name('employees.downloadTaxTop3');
        Route::get('/download-managers', fn() => response()->download(storage_path('app/export/output_managers.csv')))->name('employees.downloadManagers');
        
        Route::get('/employees', [EmployeeCrudController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeCrudController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeCrudController::class, 'store'])->name('employees.store');
        Route::get('/employees/{id}/edit', [EmployeeCrudController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{id}', [EmployeeCrudController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{id}', [EmployeeCrudController::class, 'destroy'])->name('employees.destroy');
        Route::delete('/api/employees/{id}', [EmployeeCrudController::class, 'destroyApi']);
    });
});


<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class EmployeeCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['department', 'position'])->get();
        
        // Tính toán statistics
        $statistics = [
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'total_positions' => Position::count(),
            'avg_salary' => Employee::avg('base_salary') ?? 0,
            'under_30_count' => Employee::whereRaw("TIMESTAMPDIFF(YEAR, birthday, CURDATE()) < 30")->count(),
        ];
        
        return view('pages.home', compact('employees', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();

        return view('pages.employee.create', compact('departments', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'emp_id' => 'required|unique:employees,emp_id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|unique:employees,email',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'base_salary' => 'required|numeric|min:0',
            'birthday' => 'required|date'
        ]);

        $data['actual_salary'] = $data['base_salary'] * 1.5;

        Employee::create($data);

        return redirect()
            ->route('home')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = Employee::findOrFail($id);

        $departments = Department::all();
        $positions = Position::all();

        return view('pages.employee.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|unique:employees,email,' . $id . ',emp_id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'base_salary' => 'required|numeric|min:0',
            'birthday' => 'required|date'
        ]);

        $data['actual_salary'] = $data['base_salary'] * 1.5;

        $employee->update($data);

        return redirect()
            ->route('home')
            ->with('success', 'Cập nhật nhân viên thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();

        return redirect()
            ->route('home')
            ->with('success', 'Xóa nhân viên thành công!');
    }
}
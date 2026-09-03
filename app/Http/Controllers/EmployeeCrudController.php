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
        $totalEmployees = Employee::count();
        
        $employees = Employee::with(['department', 'position'])->get();
        
        $statistics = [
            'total_employees' => $totalEmployees,
            'total_departments' => Department::count(),
            'total_positions' => Position::count(),
            'avg_salary' => Employee::avg('base_salary') ?? 0,
            'under_30_count' => Employee::whereRaw("TIMESTAMPDIFF(YEAR, birthday, CURDATE()) < 30")->count(),
        ];
        
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        
        return view('pages.home', compact('employees', 'statistics', 'isAdmin', 'totalEmployees'));
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
            'emp_id' => 'required|string|max:20|unique:employees,emp_id',
            'full_name' => 'required|string|max:100',
            'email' => ['required', 'email:rfc,dns', 'unique:employees,email'],
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'base_salary' => 'required|numeric|min:0|max:999999999999',
            'birthday' => ['required', 'date_format:Y-m-d', 'before_or_equal:today']
        ], [
            'emp_id.required' => 'Mã nhân viên là bắt buộc',
            'emp_id.string' => 'Mã nhân viên phải là chuỗi',
            'emp_id.max' => 'Mã nhân viên tối đa 20 ký tự',
            'emp_id.unique' => 'Mã nhân viên đã tồn tại',
            'full_name.required' => 'Họ tên là bắt buộc',
            'full_name.string' => 'Họ tên phải là chuỗi',
            'full_name.max' => 'Họ tên tối đa 100 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'department_id.required' => 'Phòng ban là bắt buộc',
            'department_id.exists' => 'Phòng ban không hợp lệ',
            'position_id.required' => 'Chức vụ là bắt buộc',
            'position_id.exists' => 'Chức vụ không hợp lệ',
            'base_salary.required' => 'Lương cơ bản là bắt buộc',
            'base_salary.numeric' => 'Lương cơ bản phải là số',
            'base_salary.min' => 'Lương cơ bản không được âm',
            'base_salary.max' => 'Lương cơ bản tối đa 12 chữ số',
            'birthday.required' => 'Ngày sinh là bắt buộc',
            'birthday.date_format' => 'Ngày sinh không hợp lệ (ngày không tồn tại hoặc sai định dạng YYYY-MM-DD)',
            'birthday.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại'
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
        $employee = Employee::where('emp_id', $id)->first();
        
        if (!$employee) {
            return redirect()
                ->route('home')
                ->with('error', 'Nhân viên không tồn tại hoặc đã bị xóa!');
        }

        $departments = Department::all();
        $positions = Position::all();

        return view('pages.employee.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::where('emp_id', $id)->first();
        
        if (!$employee) {
            return redirect()
                ->route('home')
                ->with('error', 'Nhân viên không tồn tại hoặc đã bị xóa!');
        }

        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => ['required', 'email:rfc,dns', 'unique:employees,email,' . $id . ',emp_id'],
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'base_salary' => 'required|numeric|min:0|max:999999999999',
            'birthday' => ['required', 'date_format:Y-m-d', 'before_or_equal:today']
        ], [
            'full_name.required' => 'Họ tên là bắt buộc',
            'full_name.string' => 'Họ tên phải là chuỗi',
            'full_name.max' => 'Họ tên tối đa 100 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'department_id.required' => 'Phòng ban là bắt buộc',
            'department_id.exists' => 'Phòng ban không hợp lệ',
            'position_id.required' => 'Chức vụ là bắt buộc',
            'position_id.exists' => 'Chức vụ không hợp lệ',
            'base_salary.required' => 'Lương cơ bản là bắt buộc',
            'base_salary.numeric' => 'Lương cơ bản phải là số',
            'base_salary.min' => 'Lương cơ bản không được âm',
            'base_salary.max' => 'Lương cơ bản tối đa 12 chữ số',
            'birthday.required' => 'Ngày sinh là bắt buộc',
            'birthday.date_format' => 'Ngày sinh không hợp lệ (ngày không tồn tại hoặc sai định dạng YYYY-MM-DD)',
            'birthday.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại'
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
        $employee = Employee::where('emp_id', $id)->first();
        
        if (!$employee) {
            return redirect()
                ->route('home')
                ->with('error', 'Nhân viên không tồn tại hoặc đã bị xóa!');
        }

        $employee->delete();

        return redirect()
            ->route('home')
            ->with('success', 'Xóa nhân viên thành công!');
    }

    public function indexApi(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $employees = Employee::with(['department', 'position'])
            ->orderBy('emp_id')
            ->paginate($perPage);

        $statistics = [
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'total_positions' => Position::count(),
            'avg_salary' => Employee::avg('base_salary') ?? 0,
            'under_30_count' => Employee::whereRaw("TIMESTAMPDIFF(YEAR, birthday, CURDATE()) < 30")->count(),
        ];

        return response()->json([
            'success' => true,
            'employees' => $employees->items(),
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
                'last_page' => $employees->lastPage(),
            ],
            'statistics' => $statistics,
        ]);
    }

        public function destroyApi(string $id, Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $currentPage = $request->input('page', 1);

        $employee = Employee::where('emp_id', $id)->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại hoặc đã bị xóa!',
            ], 404);
        }
        
        $employee->delete();

        $total = Employee::count();
        
        if ($total === 0) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa nhân viên thành công!',
                'employees' => [],
                'statistics' => [
                    'total_employees' => 0,
                    'total_departments' => Department::count(),
                    'total_positions' => Position::count(),
                    'avg_salary' => 0,
                    'under_30_count' => 0,
                ],
                'pagination' => [
                    'current_page' => 1,
                    'per_page' => $perPage,
                    'total' => 0,
                    'last_page' => 1,
                ],
            ]);
        }
        
        $lastPage = max(1, (int)ceil($total / $perPage));
        if ($currentPage > $lastPage) {
            $currentPage = $lastPage;
        }

        $employees = Employee::with(['department', 'position'])
            ->orderBy('emp_id')
            ->offset(($currentPage - 1) * $perPage)
            ->limit($perPage)
            ->get();

        $statistics = [
            'total_employees' => $total,
            'total_departments' => Department::count(),
            'total_positions' => Position::count(),
            'avg_salary' => Employee::avg('base_salary') ?? 0,
            'under_30_count' => Employee::whereRaw("TIMESTAMPDIFF(YEAR, birthday, CURDATE()) < 30")->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Xóa nhân viên thành công!',
            'employees' => $employees,
            'statistics' => $statistics,
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => $lastPage,
            ],
        ]);
    }
}
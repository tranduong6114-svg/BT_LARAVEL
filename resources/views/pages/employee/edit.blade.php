@extends('layouts.app')

@section('title', 'Sửa Nhân Viên')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 25px;">
        <h2 style="text-align: center; margin-bottom: 20px; color: #333;">Sửa Nhân Viên</h2>
        
        <form method="POST" action="{{ route('employees.update', $employee->emp_id) }}">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Mã NV</label>
                <input type="text" 
                       value="{{ $employee->emp_id }}" 
                       disabled 
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; background-color: #eee; color: #666;">
                <span style="color: #999; font-size: 12px; margin-top: 5px; display: block;">Mã NV không thể thay đổi</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Họ Tên <span style="color: red;">*</span></label>
                <input type="text" 
                       name="full_name" 
                       value="{{ $employee->full_name }}" 
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       required>
                @error('full_name')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Email <span style="color: red;">*</span></label>
                <input type="email" 
                       name="email" 
                       value="{{ $employee->email }}" 
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       required>
                @error('email')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Phòng Ban <span style="color: red;">*</span></label>
                <select name="department_id" 
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                        required>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ $employee->department_id == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Chức Vụ <span style="color: red;">*</span></label>
                <select name="position_id" 
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                        required>
                    @foreach($positions as $p)
                        <option value="{{ $p->id }}" {{ $employee->position_id == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                @error('position_id')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Lương Cơ Bản <span style="color: red;">*</span></label>
                <input type="number" 
                       name="base_salary" 
                       value="{{ $employee->base_salary }}" 
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       required>
                <span style="color: #999; font-size: 12px; margin-top: 5px; display: block;">Lương thực hiện tại: <strong>{{ number_format($employee->actual_salary, 0, ',', '.') }} đ</strong> (tự động cập nhật khi đổi lương CB)</span>
                @error('base_salary')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Ngày Sinh <span style="color: red;">*</span></label>
                <input type="date" 
                       name="birthday" 
                       value="{{ $employee->birthday }}" 
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       required>
                @error('birthday')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <button type="submit" 
                        style="flex: 1; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                    Cập Nhật
                </button>
                <a href="{{ route('home') }}" 
                   style="flex: 1; padding: 12px; background-color: #6c757d; color: white; text-align: center; border-radius: 4px; text-decoration: none;">
                    Hủy
                </a>
            </div>
        </form>
        
        <form method="POST" 
              action="{{ route('employees.destroy', $employee->emp_id) }}" 
              onsubmit="return confirm('Bạn có chắc muốn xóa nhân viên này? Hành động này không thể hoàn tác.');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    style="width: 100%; padding: 12px; background-color: #dc3545; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                Xóa Nhân Viên
            </button>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Thêm nhân viên')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 25px;">
        <h2 style="text-align: center; margin-bottom: 20px; color: #333;">Thêm Nhân Viên</h2>

        <form method="POST" action="{{ route('employees.store') }}" novalidate>
            @csrf

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Mã NV <span style="color: red;">*</span></label>
                <input type="text"
                       name="emp_id"
                       value="{{ old('emp_id') }}"
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       required>
                @error('emp_id')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Họ tên <span style="color: red;">*</span></label>
                <input type="text"
                       name="full_name"
                       value="{{ old('full_name') }}"
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
                       value="{{ old('email') }}"
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
                    <option value="">-- Chọn Phòng Ban --</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ old('department_id') == $d->id ? 'selected' : '' }}>
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
                    <option value="">-- Chọn Chức Vụ --</option>
                    @foreach($positions as $p)
                        <option value="{{ $p->id }}" {{ old('position_id') == $p->id ? 'selected' : '' }}>
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
                       value="{{ old('base_salary') }}" 
                       placeholder="VD: 10000000"
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       required>
                <span style="color: #999; font-size: 12px; margin-top: 5px; display: block;">Lương thực sẽ được tự động tính (Lương CB × 1.5)</span>
                @error('base_salary')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Ngày Sinh <span style="color: red;">*</span></label>
                <input type="text" 
                       name="birthday" 
                       value="{{ old('birthday') }}" 
                       max="{{ date('Y-m-d') }}"
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       required>
                @error('birthday')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" 
                        style="flex: 1; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                    Lưu
                </button>
                <a href="{{ route('home') }}" 
                   style="flex: 1; padding: 12px; background-color: #6c757d; color: white; text-align: center; border-radius: 4px; text-decoration: none;">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
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
                       value="{{ old('full_name', $employee->full_name) }}" 
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
                       value="{{ old('email', $employee->email) }}" 
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
                        <option value="{{ $d->id }}" {{ old('department_id', $employee->department_id) == $d->id ? 'selected' : '' }}>
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
                        <option value="{{ $p->id }}" {{ old('position_id', $employee->position_id) == $p->id ? 'selected' : '' }}>
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
                       value="{{ old('base_salary', $employee->base_salary) }}"
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       required>
                <span style="color: #999; font-size: 12px; margin-top: 5px; display: block;">Lương thực hiện tại: <strong>{{ number_format($employee->actual_salary, 0, ',', '.') }} đ</strong> (tự động cập nhật khi đổi lương CB)</span>
                @error('base_salary')
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Ngày Sinh <span style="color: red;">*</span></label>
                <input type="text" 
                       name="birthday" 
                       value="{{ old('birthday', $employee->birthday) }}"
                       max="{{ date('Y-m-d') }}"
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
              id="formDeleteEmployee"
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        <button type="button" id="btnDeleteEmployee" data-id="{{ $employee->emp_id }}" data-name="{{ htmlspecialchars($employee->full_name, ENT_QUOTES, 'UTF-8') }}"
                style="width: 100%; padding: 12px; background-color: #dc3545; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
            Xóa Nhân Viên
        </button>
    </div>
</div>
<div id="modalDeleteConfirm" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; width: 400px; padding: 25px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h5 style="margin: 0 0 15px 0; color: #333;">Xác nhận xóa</h5>
        <p id="modalDeleteText" style="color: #666; margin-bottom: 20px;"></p>
        <div style="text-align: right;">
            <button id="btnCancelDelete" style="padding: 8px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">Hủy</button>
            <button id="btnConfirmDelete" style="padding: 8px 20px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Xác nhận xóa</button>
        </div>
    </div>
</div>
<div id="modalResult" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; justify-content: center; align-items: center;">
    <div style="background: white; width: 400px; padding: 25px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h5 id="modalResultTitle" style="margin: 0 0 15px 0;"></h5>
        <p id="modalResultText" style="color: #666; margin-bottom: 20px;"></p>
        <div style="text-align: right;">
            <button id="btnCloseResult" style="padding: 8px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Đóng</button>
        </div>
    </div>
</div>
@endsection
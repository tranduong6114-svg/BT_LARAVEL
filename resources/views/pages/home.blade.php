@extends('layouts.app')

@section('title', 'Trang Chủ')

@auth
    @section('laravel-data')
        employees: @json($employees),
        statistics: @json($statistics),
    @endsection
@endauth

@section('content')
<div style="margin-bottom: 20px;">
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
        <div class="stat-card" style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center;">
            <h3 id="sTotal" style="font-size: 32px; margin-bottom: 5px; color: #007bff;">0</h3>
            <p style="margin: 0; color: #666;">Tổng NV</p>
        </div>
        <div class="stat-card" style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center;">
            <h3 id="sDept" style="font-size: 32px; margin-bottom: 5px; color: #28a745;">0</h3>
            <p style="margin: 0; color: #666;">Phòng Ban</p>
        </div>
        <div class="stat-card" style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center;">
            <h3 id="sPos" style="font-size: 32px; margin-bottom: 5px; color: #6f42c1;">0</h3>
            <p style="margin: 0; color: #666;">Chức Vụ</p>
        </div>
        <div class="stat-card" style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center;">
            <h3 id="sAvg" style="font-size: 32px; margin-bottom: 5px; color: #ffc107;">0</h3>
            <p style="margin: 0; color: #666;">Lương TB</p>
        </div>
        <div class="stat-card" style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center;">
            <h3 id="sU30" style="font-size: 32px; margin-bottom: 5px; color: #dc3545;">0</h3>
            <p style="margin: 0; color: #666;">NV dưới 30t</p>
        </div>
    </div>
</div>

<div id="employeeCard" style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;">
        <h5 style="margin: 0; color: #333;">Danh Sách Nhân Viên</h5>
        <div style="display: flex; gap: 10px;">
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('employees.create') }}" style="padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                + Thêm Nhân Viên
            </a>
            @endif
            <button id="btnReload" style="padding: 8px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Tải Lại
            </button>
        </div>
    </div>
    <div style="padding: 15px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #343a40; color: white;">
                    <th style="padding: 12px; text-align: left;">Mã NV</th>
                    <th style="padding: 12px; text-align: left;">Họ Tên</th>
                    <th style="padding: 12px; text-align: left;">Email</th>
                    <th style="padding: 12px; text-align: center;">Ngày Sinh</th>
                    <th style="padding: 12px; text-align: left;">Phòng Ban</th>
                    <th style="padding: 12px; text-align: left;">Chức Vụ</th>
                    <th style="padding: 12px; text-align: right;">Lương CB</th>
                    <th style="padding: 12px; text-align: right;">Lương Thực</th>
                    <th style="padding: 12px; text-align: center;">Hành Động</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($employees as $e)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 12px;"><strong>{{ $e->emp_id }}</strong></td>
                    <td style="padding: 12px;">{{ $e->full_name }}</td>
                    <td style="padding: 12px;">{{ $e->email }}</td>
                    <td style="padding: 12px; text-align: center;">{{ $e->birthday ? \Carbon\Carbon::parse($e->birthday)->format('d/m/Y') : 'N/A' }}</td>
                    <td style="padding: 12px;">{{ $e->department->name ?? 'N/A' }}</td>
                    <td style="padding: 12px;">{{ $e->position->name ?? 'N/A' }}</td>
                    <td style="padding: 12px; text-align: right;">{{ number_format($e->base_salary, 0, ',', '.') }} đ</td>
                    <td style="padding: 12px; text-align: right;">{{ number_format($e->actual_salary, 0, ',', '.') }} đ</td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('employees.edit', $e->emp_id) }}" style="color: #007bff; text-decoration: none; margin-right: 10px;">Sửa</a>
                        <form action="{{ route('employees.destroy', $e->emp_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Xóa nhân viên này?')" style="color: #dc3545; background: none; border: none; cursor: pointer; padding: 0;">Xóa</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 10px;
        }
        table {
            font-size: 12px;
        }
    }
</style>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/pages/home.js') }}"></script>
@endpush
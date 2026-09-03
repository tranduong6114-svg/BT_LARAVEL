@extends('layouts.app')

@section('title', 'Trang Chủ')

@auth
    @section('laravel-data')
        employees: @json($employees, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
        statistics: @json($statistics),
        isAdmin: @json($isAdmin ?? false),
        csrfToken: '{{ csrf_token() }}',
        pagination: {
            current_page: 1,
            per_page: 10,
            total: {{ $totalEmployees }},
            last_page: {{ max(1, ceil($totalEmployees / 10)) }}
        }
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
                        <button type="button" class="btn-delete" data-id="{{ $e->emp_id }}" data-name="{{ htmlspecialchars($e->full_name, ENT_QUOTES, 'UTF-8') }}" style="color: #dc3545; background: none; border: none; cursor: pointer; padding: 0;">Xóa</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="pagination" style="display: flex; justify-content: center; align-items: center; padding: 15px; gap: 5px;"></div>
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

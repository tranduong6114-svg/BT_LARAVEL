@extends('layouts.app')

@section('title', 'Thống Kê')

@section('content')
<style>
    .btn-download {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 15px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
    }
    .btn-download:hover {
        background-color: #5a6268;
    }
</style>
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
        <h3 style="margin-bottom: 15px; color: #333;">Lương nhân viên dưới 30 tuổi</h3>
        <p style="font-size: 18px; color: #666;">Lương trung bình: <strong id="avgSalaryUnder30">Đang tải...</strong></p>
        <a href="{{ route('employees.salaryUnder30') }}" class="btn" style="display: inline-block; margin-top: 10px; padding: 8px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">Xem chi tiết</a>
    </div>
    
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
        <h3 style="margin-bottom: 15px; color: #333;">Trưởng phòng (Quản lý)</h3>
        <p style="font-size: 18px; color: #666;">Tổng số: <strong id="managerCount">Đang tải...</strong></p>
        <a href="{{ route('employees.exportManagers') }}" class="btn" style="display: inline-block; margin-top: 10px; padding: 8px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px;">Xuất Excel</a>
        <a href="{{ route('employees.downloadManagers') }}" class="btn-download">Tải về</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
        <h3 style="margin-bottom: 15px; color: #333;">Xuất BHXH</h3>
        <p style="color: #666;">Xuất danh sách đóng BHXH</p>
        <a href="{{ route('employees.exportBhxh') }}" class="btn" style="display: inline-block; margin-top: 10px; padding: 8px 15px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 4px;">Xuất BHXH</a>
        <a href="{{ route('employees.downloadBhxh') }}" class="btn-download">Tải về</a>
    </div>
    
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
        <h3 style="margin-bottom: 15px; color: #333;">Thuế TNCN</h3>
        <p style="color: #666;">Xuất danh sách thuế thu nhập</p>
        <a href="{{ route('employees.exportTax') }}" class="btn" style="display: inline-block; margin-top: 10px; padding: 8px 15px; background-color: #ffc107; color: white; text-decoration: none; border-radius: 4px;">Xuất Thuế</a>
        <a href="{{ route('employees.downloadTax') }}" class="btn-download">Tải về</a>
    </div>
    
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
        <h3 style="margin-bottom: 15px; color: #333;">Top 3 Thuế Cao</h3>
        <p style="color: #666;">Top 3 nhân viên thuế cao nhất</p>
        <a href="{{ route('employees.exportTaxTop3') }}" class="btn" style="display: inline-block; margin-top: 10px; padding: 8px 15px; background-color: #6f42c1; color: white; text-decoration: none; border-radius: 4px;">Xem Top 3</a>
        <a href="{{ route('employees.downloadTaxTop3') }}" class="btn-download">Tải về</a>
    </div>
</div>
@endsection

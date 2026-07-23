@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div style="display: flex; justify-content: center; padding-top: 50px;">
    <div style="width: 100%; max-width: 400px;">
        <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2 style="text=align: center; margin-bottom: 25px; color: #333;">Đăng ký</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf 

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Họ tên</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                           required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Email</label>
                    <input type="email"
                           name="email"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                           required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Mật khẩu</label>
                    <input type="password"
                           name="password"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                           required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Xác nhận mật khẩu</label>
                    <input type="password"
                           name="password_confirmation"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                           required>
                </div>

                @error('name')
                    <p style="color: red; font-size: 13px; margin-bottom: 10px;">{{ $message }}</p>
                @enderror

                @error('email')
                    <p style="color: red; font-size: 13px; margin-bottom: 10px;">{{ $message }}</p>
                @enderror

                @error('password')
                    <p style="color: red; font-size: 13px; margin-bottom: 10px;">{{ $message }}</p>
                @enderror

                <button type="submit"
                        style="width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                    Đăng ký
                </button>
            </form>

            <p style="text-align: center; margin-top: 20px; color: #666;">
                Đã có tài khoản? <a href="{{ route('login') }}" style="color: #007bff; text-decoration: none;">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>
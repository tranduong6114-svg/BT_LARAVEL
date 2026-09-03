@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div style="display: flex; justify-content: center; padding-top: 50px;">
    <div style="width: 100%; max-width: 400px;">
        <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2 style="text=align: center; margin-bottom: 25px; color: #333;">Đăng nhập</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf 

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                           required autofocus>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Mật khẩu</label>
                    <input type="password"
                           name="password"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                           required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                    </label>
                </div>

                @error('email')
                    <p style="color: red; font-size: 13px; margin-bottom: 10px;">{{ $message }}</p>
                @enderror

                <button type="submit"
                        style="width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                    Đăng nhập
                </button>
            </form>

            <p style="text-align: center; margin-top: 20px; color: #666;">
                Chưa có tài khoản? <a href="{{ route('register') }}" style="color: #007bff; text-decoration: none;">Đăng ký</a>
            </p>
        </div>
    </div>
</div>
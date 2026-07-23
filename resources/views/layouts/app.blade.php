<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QL Nhân Viên')</title>

    <script>
        window.LaravelData = {
            csrfToken: "{{ csrf_token() }}",
            @auth
                isAdmin: {{ auth()->user()->role === 'admin' ? 'true' : 'false' }},
                @yield('laravel-data')
            @endauth
        };
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }
        
        .navbar {
            background-color: #007bff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            color: white;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
        }
        
        .navbar-brand:hover {
            color: #ddd;
        }
        
        .navbar-menu {
            display: flex;
            list-style: none;
            gap: 15px;
        }
        
        .navbar-menu a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
        }
        
        .navbar-menu a:hover {
            background-color: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        
        .navbar-menu form {
            display: inline;
        }
        
        .navbar-menu button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px 15px;
            font-size: 14px;
        }
        
        .navbar-menu button:hover {
            background-color: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .main-content {
            padding: 20px 0;
        }
        
        .alert {
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }
            
            .navbar-menu {
                flex-direction: column;
                width: 100%;
                gap: 5px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 0;">
            <a class="navbar-brand" href="{{ url('/') }}">QL Nhân Viên</a>
            <ul class="navbar-menu">
                <li><a href="{{ url('/') }}">Trang Chủ</a></li>
                @auth
                    <li><a href="{{ route('employees.statistics') }}">Thống Kê</a></li>
                @endauth
                @auth
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">{{ Auth::user()->name }} (Đăng xuất)</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Đăng Nhập</a></li>
                    <li><a href="{{ route('register') }}">Đăng Ký</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="container main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
    </div>

    <main class="container main-content">
        @yield('content')
    </main>
    
    @stack('scripts')
</body>
</html>
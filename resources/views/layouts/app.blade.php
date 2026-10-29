<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Karhutla')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #222222;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 24px;
            border-bottom: 1px solid #cccccc;
        }
        .navbar .brand {
            font-weight: bold;
            font-size: 15px;
            text-decoration: none;
            color: #000000;
        }
        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
        }
        .badge-role {
            font-size: 11px;
            padding: 2px 6px;
            border: 1px solid #888888;
            text-transform: uppercase;
        }
        .btn-logout {
            padding: 4px 10px;
            font-size: 12px;
            border: 1px solid #333333;
            background-color: #ffffff;
            color: #333333;
            cursor: pointer;
        }
        .btn-logout:hover {
            background-color: #333333;
            color: #ffffff;
        }
        .container {
            max-width: 900px;
            margin: 24px auto;
            padding: 0 16px;
        }
        .alert {
            padding: 10px;
            border: 1px solid #cccccc;
            background-color: #f8f8f8;
            font-size: 13px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="{{ route('dashboard') }}" class="brand">Sistem Karhutla</a>
        
        <div class="user-info">
            <span>{{ auth()->user()->nama_lengkap }}</span>
            <span class="badge-role">{{ auth()->user()->role }}</span>
            
            <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        @if (session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Безумно</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .admin-body {
            background: #f0f2f5;
        }
        .admin-header {
            background: #1a1a1a;
            padding: 15px 30px;
            border-bottom: 3px solid #FF6B00;
            margin-bottom: 30px;
        }
        .admin-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }
        .admin-logo {
            font-size: 24px;
            font-weight: bold;
            color: #FF6B00;
            text-decoration: none;
        }
        .admin-logo span {
            color: white;
        }
        .admin-nav {
            display: flex;
            gap: 20px;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .admin-nav a:hover, .admin-nav a.active {
            background: #FF6B00;
        }
        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }
        .logout-btn {
            background: #333;
            padding: 8px 20px;
            border-radius: 8px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .logout-btn:hover {
            background: #e74c3c;
        }
        .admin-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }
        .admin-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .admin-table th {
            background: #1a1a1a;
            color: white;
            padding: 12px 15px;
            text-align: left;
        }
        .admin-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        .admin-table tr:hover {
            background: #f9f9f9;
        }
        .badge-active {
            background: #27ae60;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }
        .badge-inactive {
            background: #95a5a6;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }

        /* ===== КНОПКИ-ИКОНКИ ===== */
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            padding: 0;
            background: #FF6B00 !important;
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-icon:hover {
            opacity: 0.8;
            transform: scale(1.05);
            background: #FF6B00 !important;
            color: white !important;
        }

        /* Кнопка статистики */
        .btn-icon-stats {
            background: #f39c12 !important;
        }

        .btn-icon-stats:hover {
            background: #f39c12 !important;
        }

        /* Кнопка активации (зелёная) */
        .btn-icon-success {
            background: #27ae60 !important;
        }

        .btn-icon-success:hover {
            background: #27ae60 !important;
        }

        /* Кнопка деактивации (красная) */
        .btn-icon-danger {
            background: #e74c3c !important;
        }

        .btn-icon-danger:hover {
            background: #e74c3c !important;
        }

        /* Отступы для таблицы */
        .admin-table td {
            vertical-align: middle;
        }
        .btn-reset {
            background: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
        }

        .btn-reset:hover {
            background: #7f8c8d;
            transform: translateY(-1px);
        }
        .btn-icon-warning {
            background: #f39c12 !important;
        }

        .btn-icon-success {
            background: #27ae60 !important;
        }

        .btn-reset {
            display: inline-block;
            padding: 10px 25px;
            background: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-reset:hover {
            background: #7f8c8d;
        }
    </style>
</head>
<body class="admin-body">
<div class="admin-header">
    <div class="admin-header-content">
        <a href="{{ route('admin.dashboard') }}" class="admin-logo">Безумно. <span>Админка</span></a>
        <div class="admin-nav">
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Товары</a>
            <a href="{{ route('admin.promocodes.index') }}" class="{{ request()->routeIs('admin.promocodes.*') ? 'active' : '' }}">Промокоды</a>
            <a href="{{ route('admin.pickup-points.index') }}" class="{{ request()->routeIs('admin.pickup-points.*') ? 'active' : '' }}">ПВЗ</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Пользователи</a>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Статистика</a>
        </div>
        <div class="admin-user">
            <span>👑 {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Выйти</button>
            </form>
        </div>
    </div>
</div>

<div class="admin-content">
    @if(session('success'))
        <div class="success" style="margin-bottom: 20px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error" style="margin-bottom: 20px;">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

<script>
    setTimeout(function() {
        document.querySelectorAll('.success, .error').forEach(msg => {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        });
    }, 3000);
</script>
</body>
</html>

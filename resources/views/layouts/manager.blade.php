{{-- resources/views/layouts/manager.blade.php --}}
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Безумно - Панель управления</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Специальные стили для менеджера */
        .manager-body {
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .manager-header {
            background: #1a1a1a;
            padding: 15px 30px;
            border-bottom: 3px solid #FF6B00;
            margin-bottom: 30px;
        }

        .manager-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .manager-logo {
            font-size: 28px;
            font-weight: bold;
            color: #FF6B00;
            text-decoration: none;
        }

        .manager-logo span {
            color: white;
        }

        .manager-nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .manager-nav a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .manager-nav a:hover {
            background: #FF6B00;
        }

        .manager-nav .active {
            background: #FF6B00;
        }

        .manager-user {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .manager-user-name {
            color: #FF6B00;
            font-weight: bold;
        }

        .logout-manager-btn {
            background: #333;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
        }

        .logout-manager-btn:hover {
            background: #e74c3c;
        }

        .manager-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #FF6B00;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body class="manager-body">
<div class="manager-header">
    <div class="manager-header-content">
        <a href="{{ route('manager.orders') }}" class="manager-logo">Безумно. <span>Управление</span></a>
        <div class="manager-nav">
            <a href="{{ route('manager.orders') }}" class="{{ request()->routeIs('manager.orders') ? 'active' : '' }}">
                📋 Все заказы
            </a>
            <a href="{{ route('manager.orders', ['status' => 'NEW']) }}" class="{{ request('status') == 'NEW' ? 'active' : '' }}">
                🆕 Новые
            </a>
            <a href="{{ route('manager.orders', ['status' => 'COOKING']) }}" class="{{ request('status') == 'COOKING' ? 'active' : '' }}">
                🍳 Готовятся
            </a>
        </div>
        <div class="manager-user">
            <span>👨‍💼 <span class="manager-user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-manager-btn">Выйти</button>
            </form>
        </div>
    </div>
</div>

<div class="manager-content">
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

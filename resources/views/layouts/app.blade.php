<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Безумно - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="header">
    <div class="header-content">
        <a href="/" class="logo">Безумно.</a>
        <div class="nav">
            <a href="/">Главная</a>
            <a href="{{ route('menu') }}">Меню</a>
            <a href="/cart">Корзина</a>
        </div>
        <div class="user-info">
            @auth
                <span class="user-name">{{ Auth::user()->first_name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn">Вход</a>
                <a href="{{ route('register') }}" class="btn">Регистрация</a>
            @endauth
        </div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>

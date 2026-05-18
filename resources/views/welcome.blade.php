<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Безумно - Главная</title>
    <link rel="stylesheet" href="{{ asset('../css/style.css') }}">
</head>
<body>
<div class="container">
    <div class="card welcome">
        <h1>🍟 Безумно 🍔</h1>
        <p>Добро пожаловать, {{ Auth::user()->first_name ?? 'гость' }}!</p>

        @auth
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn">Выйти</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn">Войти</a>
            <a href="{{ route('register') }}" class="btn">Регистрация</a>
        @endauth
    </div>
</div>
</body>
</html>

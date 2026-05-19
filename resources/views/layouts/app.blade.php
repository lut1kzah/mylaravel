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
            @auth
                <a href="{{ route('profile') }}">Профиль</a>
            @endauth
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
<script>
    // Автоматическое скрытие сообщений через 3 секунды
    setTimeout(function() {
        let successMessages = document.querySelectorAll('.success');
        let errorMessages = document.querySelectorAll('.error');

        successMessages.forEach(function(msg) {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(function() {
                msg.remove();
            }, 500);
        });

        errorMessages.forEach(function(msg) {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(function() {
                msg.remove();
            }, 500);
        });
    }, 3000);

    // Кнопки + и - для корзины (исправленная версия)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.quantity-form').forEach(form => {
            const minusBtn = form.querySelector('.qty-btn.minus');
            const plusBtn = form.querySelector('.qty-btn.plus');
            const input = form.querySelector('.quantity-input');

            if (minusBtn) {
                minusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let currentVal = parseInt(input.value);
                    if (currentVal > 1) {
                        input.value = currentVal - 1;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        form.submit();
                    }
                });
            }

            if (plusBtn) {
                plusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let currentVal = parseInt(input.value);
                    input.value = currentVal + 1;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    form.submit();
                });
            }

            if (input) {
                input.addEventListener('change', function() {
                    form.submit();
                });
            }
        });
    });
</script>
</html>

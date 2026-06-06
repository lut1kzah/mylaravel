<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Безумно - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="guest-body">

<!-- Фоновые надписи для всех страниц -->
<div class="guest-background" id="guestBackground"></div>
@if(auth()->check() && auth()->user()->role_id == 2)
    <a href="{{ route('manager.orders') }}">Управление заказами</a>
@endif
<!-- Шапка для авторизованных -->
@auth
    <div class="header">
        <div class="header-content">
            <a href="/menu" class="logo">Безумно.</a>
            <div class="nav">
                <a href="{{ route('menu') }}">Меню</a>
                <a href="/cart">Корзина</a>
                <a href="{{ route('active.orders') }}">Мои заказы</a>
                <a href="{{ route('profile') }}">Профиль</a>
            </div>
            <div class="user-info">
                <span class="user-name">Привет, {{ Auth::user()->first_name }}!</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>
    </div>
@endauth

<div class="content">
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @yield('content')

</div>
@include('layouts.footer')
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

    // Кнопки + и - для корзины
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

<!-- Рандомные надписи на фоне -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const background = document.getElementById('guestBackground');
        if (!background) return;

        background.innerHTML = '';
        const words = ['Безумно.', 'Безумно.', 'Безумно.', 'Безумно.', 'Безумно.'];
        const count = 35;

        for (let i = 0; i < count; i++) {
            const word = words[Math.floor(Math.random() * words.length)];
            const span = document.createElement('div');
            span.className = 'floating-text';
            span.textContent = word;

            const top = Math.random() * 90 + 5;
            const left = Math.random() * 90 + 5;
            const fontSize = Math.floor(Math.random() * 50 + 32);
            const opacity = Math.random() * 0.5 + 0.25;
            const rotate = Math.random() * 12 - 6;

            span.style.top = top + '%';
            span.style.left = left + '%';
            span.style.fontSize = fontSize + 'px';
            span.style.opacity = opacity;
            span.style.transform = `rotate(${rotate}deg)`;

            background.appendChild(span);
        }
    });
</script>
@stack('scripts')
<!-- Кнопка "Наверх" -->
<button id="scrollToTopBtn" class="scroll-to-top" title="Наверх">↑</button>

<script>
    // Кнопка "Наверх"
    const scrollBtn = document.getElementById('scrollToTopBtn');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });

    scrollBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>

</body>
</html>

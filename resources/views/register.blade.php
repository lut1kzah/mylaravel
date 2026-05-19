<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - Безумно</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="container">
    <div class="card">
        <h2>🍔 Регистрация</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row">
                <div class="form-group">
                    <label>Фамилия *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required>
                    @error('last_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Имя *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required>
                    @error('first_name') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Отчество</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}">
                @error('middle_name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Пол *</label>
                <div style="display: flex; gap: 20px; margin-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 5px;">
                        <input type="radio" name="gender" value="1" required> Мужчина
                    </label>
                    <label style="display: flex; align-items: center; gap: 5px;">
                        <input type="radio" name="gender" value="0" required> Женщина
                    </label>
                </div>
                @error('gender') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Телефон *</label>
                <input type="tel" name="phone" placeholder="+7XXXXXXXXXX" value="{{ old('phone') }}" required>
                @error('phone') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Пароль *</label>
                <input type="password" name="password" required>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Подтверждение пароля *</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit">Зарегистрироваться</button>

            <div class="login-link">
                Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>

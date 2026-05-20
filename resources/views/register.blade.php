@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">Регистрация</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row">
                    <div class="form-group">
                        <input type="text" name="last_name" placeholder="Фамилия" value="{{ old('last_name') }}" required>
                        @error('last_name') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <input type="text" name="first_name" placeholder="Имя" value="{{ old('first_name') }}" required>
                        @error('first_name') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" name="middle_name" placeholder="Отчество" value="{{ old('middle_name') }}">
                    @error('middle_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Телефон" value="{{ old('phone') }}" required>
                    @error('phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="gender-label">Пол</label>
                    <div class="gender-group">
                        <label><input type="radio" name="gender" value="1" required> Мужчина</label>
                        <label><input type="radio" name="gender" value="0" required> Женщина</label>
                    </div>
                    @error('gender') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Пароль" required>
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password_confirmation" placeholder="Подтверждение пароля" required>
                </div>

                <button type="submit" class="auth-btn">Зарегистрироваться</button>

                <div class="auth-link">
                    <a href="{{ route('login') }}">Уже есть аккаунт? Войти</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">Вход</h2>

            @if($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Пароль" required>
                </div>
                <button type="submit" class="auth-btn">Войти</button>
            </form>

            <div class="auth-link">
                <a href="{{ route('register') }}">Нет аккаунта? Зарегистрироваться</a>
            </div>
        </div>
    </div>
@endsection

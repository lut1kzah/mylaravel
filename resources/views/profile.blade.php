@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
    <div class="profile-container">
        <div class="profile-card">
            <h2>👤 Личный кабинет</h2>

            <div class="profile-info">
                <div class="info-row">
                    <span class="info-label">Фамилия:</span>
                    <span class="info-value">{{ $user->last_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Имя:</span>
                    <span class="info-value">{{ $user->first_name }}</span>
                </div>
                @if($user->middle_name)
                    <div class="info-row">
                        <span class="info-label">Отчество:</span>
                        <span class="info-value">{{ $user->middle_name }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Пол:</span>
                    <span class="info-value">
                    @if($user->gender === null)
                            Не указан
                        @elseif($user->gender == 1)
                            👨 Мужчина
                        @else
                            👩 Женщина
                        @endif
                </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Телефон:</span>
                    <span class="info-value">{{ $user->phone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Дата регистрации:</span>
                    <span class="info-value">{{ date('d.m.Y', strtotime($user->registered_at)) }}</span>
                </div>
            </div>

            <div class="bonus-section">
                <h3>🎁 Бонусная программа</h3>
                <div class="bonus-row">
                    <span class="bonus-label">Бонусный баланс:</span>
                    <span class="bonus-value">{{ number_format($user->bonus_balance, 0, ',', ' ') }} баллов</span>
                </div>
                <div class="bonus-row">
                    <span class="bonus-label">Уровень:</span>
                    <span class="bonus-value level-{{ strtolower($user->current_level) }}">
                    {{ $user->current_level }}
                </span>
                </div>
                <div class="bonus-row">
                    <span class="bonus-label">Всего потрачено:</span>
                    <span class="bonus-value">{{ number_format($user->total_spent, 0, ',', ' ') }} ₽</span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="/" class="btn">На главную</a>
                <a href="/menu" class="btn">В меню</a>
            </div>
        </div>
    </div>
@endsection

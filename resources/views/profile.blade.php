@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
    <div class="profile-container">
        <div class="profile-card">
            <h1>Личный кабинет</h1>

            <!-- Уведомление о повышении уровня -->
            @if(session('level_up'))
                <div class="level-up-notification">
                    🎉 {{ session('level_up') }} 🎉
                </div>
            @endif

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

            <!-- Прогресс до следующего уровня -->
            @if($nextLevel)
                <div class="level-progress-section">
                    <h3>📈 Мой уровень лояльности</h3>
                    <div class="level-progress">
                        <div class="level-current">
                            Текущий: <strong>{{ $user->current_level }}</strong> ({{ $cashback }}% кэшбэк)
                        </div>
                        <div class="level-next">
                            До {{ $nextLevel['level'] }}:
                            {{ number_format($nextLevel['need_to_spend'], 0, ',', ' ') }} ₽
                        </div>
                        <div class="progress-bar-container">
                            @php
                                $currentLevelMinSpent = $levelsRules[$user->current_level]['min_spent'] ?? 0;
                                $nextLevelMinSpent = $nextLevel['min_spent'];
                                $progress = 0;
                                if ($nextLevelMinSpent > $currentLevelMinSpent) {
                                    $progress = min(100, ($user->total_spent - $currentLevelMinSpent) / ($nextLevelMinSpent - $currentLevelMinSpent) * 100);
                                }
                            @endphp
                            <div class="progress-bar" style="width: {{ $progress }}%;"></div>
                        </div>
                        <div class="level-conditions">
                            <small>Условия сохранения уровня: {{ $levelsRules[$user->current_level]['min_orders_3months'] }} покупок за 3 месяца</small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Таблица всех уровней -->
            <div class="levels-table-section">
                <h4>🎁 Уровни лояльности</h4>
                <table class="levels-table">
                    <thead>
                    <tr>
                        <th>Уровень</th>
                        <th>Кэшбэк</th>
                        <th>Порог суммы</th>
                        <th>Покупок за 3 мес</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($levelsRules as $level => $rules)
                        <tr class="{{ $user->current_level == $level ? 'current-level' : '' }}">
                            <td>{{ $level }}</td>
                            <td>{{ $rules['cashback'] }}%</td>
                            <td>от {{ number_format($rules['min_spent'], 0, ',', ' ') }} ₽</td>
                            <td>{{ $rules['min_orders_3months'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="profile-actions">
                <a href="/menu" class="btn">В меню</a>
                <a href="{{ route('profile.orders') }}" class="btn">История заказов</a>
            </div>
        </div>
    </div>

    <style>
        .level-up-notification {
            background: linear-gradient(135deg, #FF6B00, #ffaa00);
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 18px;
            animation: bounce 0.5s ease;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .level-progress-section {
            background: #f9f9f9;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }

        .level-progress {
            margin-top: 15px;
        }

        .level-current, .level-next {
            margin-bottom: 10px;
        }

        .progress-bar-container {
            background: #e0e0e0;
            border-radius: 10px;
            height: 12px;
            margin: 10px 0;
            overflow: hidden;
        }

        .progress-bar {
            background: #FF6B00;
            height: 100%;
            width: 0%;
            border-radius: 10px;
            transition: width 0.5s;
        }

        .level-conditions {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
        }

        .levels-table-section {
            margin-top: 20px;
        }

        .levels-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .levels-table th, .levels-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .levels-table th {
            background: #1a1a1a;
            color: white;
        }

        .levels-table .current-level {
            background: #fff3e0;
            font-weight: bold;
        }
    </style>
@endsection

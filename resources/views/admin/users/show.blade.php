@extends('admin.layouts.admin')

@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1>👤 Пользователь: {{ $user->last_name }} {{ $user->first_name }}</h1>
            <a href="{{ route('admin.users.index') }}" class="btn" style="background: #666;">← Назад</a>
        </div>

        <!-- Основная информация -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div style="background: white; padding: 20px; border-radius: 15px;">
                <h3>📋 Личная информация</h3>
                <table style="width: 100%;">
                    <tr><td style="padding: 8px 0;"><strong>ФИО:</strong></td><td>{{ $user->last_name }} {{ $user->first_name }} {{ $user->middle_name }}</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Пол:</strong></td><td>{{ $user->gender == 1 ? 'Мужской' : ($user->gender == 0 ? 'Женский' : 'Не указан') }}</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Телефон:</strong></td><td>{{ $user->phone }}</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Email:</strong></td><td>{{ $user->email }}</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Дата регистрации:</strong></td><td>{{ date('d.m.Y H:i', strtotime($user->registered_at)) }}</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Роль:</strong></td><td>
                            @if($user->role_id == 1) 👤 Пользователь
                            @elseif($user->role_id == 2) 👨‍💼 Менеджер
                            @else 👑 Администратор
                            @endif
                        </td></tr>
                </table>
            </div>

            <div style="background: white; padding: 20px; border-radius: 15px;">
                <h3>🎁 Бонусная информация</h3>
                <table style="width: 100%;">
                    <tr><td style="padding: 8px 0;"><strong>Бонусный баланс:</strong></td><td style="color: #FF6B00; font-size: 24px;">{{ number_format($user->bonus_balance, 0, ',', ' ') }} ₽</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Уровень:</strong></td><td>{{ $user->current_level }}</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Всего потрачено:</strong></td><td>{{ number_format($user->total_spent, 0, ',', ' ') }} ₽</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Всего заказов:</strong></td><td>{{ $ordersCount }}</td></tr>
                    <tr><td style="padding: 8px 0;"><strong>Завершено заказов:</strong></td><td>{{ $completedOrders }}</td></tr>
                </table>

                <!-- Корректировка бонусов -->
                <form method="POST" action="{{ route('admin.users.adjustBonus', $user->user_id) }}" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    @csrf
                    <h4>Корректировка бонусов</h4>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <input type="number" name="amount" placeholder="Сумма (+ начисление, - списание)" required style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <input type="text" name="reason" placeholder="Причина" required style="flex: 2; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <button type="submit" class="btn" style="background: #f39c12;">Применить</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Бонусные транзакции -->
        <div style="background: white; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
            <h3>📜 История бонусных транзакций</h3>
            <table class="admin-table">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Тип</th>
                    <th>Сумма</th>
                    <th>Заказ</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bonusTransactions as $transaction)
                    <tr>
                        <td>{{ date('d.m.Y H:i', strtotime($transaction->created_at)) }}</td>
                        <td>
                            @if($transaction->type == 'EARNED')
                                <span style="color: #27ae60;">✅ Начисление</span>
                            @elseif($transaction->type == 'SPENT')
                                <span style="color: #e74c3c;">❌ Списание</span>
                            @else
                                <span style="color: #f39c12;">🔄 Возврат</span>
                            @endif
                        </td>
                        <td style="{{ $transaction->amount > 0 ? 'color: #27ae60;' : 'color: #e74c3c;' }} font-weight: bold;">
                            {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 0, ',', ' ') }} ₽
                        </td>
                        <td>{{ $transaction->order ? 'Заказ #' . $transaction->order->order_id : 'Корректировка' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center;">Нет транзакций</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $bonusTransactions->links() }}
        </div>

        <!-- Последние заказы -->
        <div style="background: white; padding: 20px; border-radius: 15px;">
            <h3>📦 Последние заказы</h3>
            <table class="admin-table">
                <thead>
                <tr><th>Заказ #</th><th>Дата</th><th>Сумма</th><th>Статус</th></tr>
                </thead>
                <tbody>
                @forelse($lastOrders as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ date('d.m.Y H:i', strtotime($order->order_date)) }}</td>
                        <td>{{ number_format($order->final_sum, 0, ',', ' ') }} ₽</td>
                        <td>
                            @if($order->status == 'NEW') 🆕 Новый
                            @elseif($order->status == 'COOKING') 🍳 Готовится
                            @elseif($order->status == 'DELIVERED') ✅ Выдан
                            @else ❌ Отменён
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center;">Нет заказов</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

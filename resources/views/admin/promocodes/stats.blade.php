@extends('admin.layouts.admin')

@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1>📊 Статистика промокода "{{ $promocode->code }}"</h1>
            <a href="{{ route('admin.promocodes.index') }}" class="btn" style="background: #666;">← Назад</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
            <div style="background: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 14px; color: #666;">Использований</div>
                <div style="font-size: 36px; font-weight: bold; color: #FF6B00;">{{ $totalUsage }}</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 14px; color: #666;">Общая скидка</div>
                <div style="font-size: 36px; font-weight: bold; color: #27ae60;">{{ number_format($totalDiscount, 0, ',', ' ') }} ₽</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 14px; color: #666;">Средний чек со скидкой</div>
                <div style="font-size: 36px; font-weight: bold; color: #FF6B00;">
                    {{ $totalUsage > 0 ? number_format($totalDiscount / $totalUsage, 0, ',', ' ') : 0 }} ₽
                </div>
            </div>
        </div>

        <h3>📋 Заказы с этим промокодом</h3>

        <table class="admin-table">
            <thead>
            <tr>
                <th>Заказ #</th>
                <th>Пользователь</th>
                <th>Дата</th>
                <th>Исходная сумма</th>
                <th>Скидка</th>
                <th>Итого</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->order_id }}</td>
                    <td>{{ $order->user->last_name }} {{ $order->user->first_name }}</td>
                    <td>{{ date('d.m.Y H:i', strtotime($order->order_date)) }}</td>
                    <td>{{ number_format($order->total_original_sum, 0, ',', ' ') }} ₽</td>
                    <td style="color: #27ae60;">-{{ number_format($order->discount_sum, 0, ',', ' ') }} ₽</td>
                    <td><strong>{{ number_format($order->final_sum, 0, ',', ' ') }} ₽</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Заказов с этим промокодом пока нет</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $orders->links() }}
        </div>
    </div>
@endsection

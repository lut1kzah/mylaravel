@extends('layouts.manager')

@section('content')
    <div>
        <!-- Статистика -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Всего заказов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #2196F3;">{{ $stats['new'] }}</div>
                <div class="stat-label">🆕 Новые</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #ff9800;">{{ $stats['cooking'] }}</div>
                <div class="stat-label">🍳 Готовятся</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #4caf50;">{{ $stats['today_orders'] }}</div>
                <div class="stat-label">📅 Заказов сегодня</div>
            </div>
        </div>

        <!-- Фильтры -->
        <div class="filters-card">
            <form method="GET" action="{{ route('manager.orders') }}" class="filters-form">
                <div class="filter-group">
                    <label>Статус:</label>
                    <select name="status">
                        <option value="">Все</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                @switch($status)
                                    @case('NEW') 🆕 Новый @break
                                    @case('COOKING') 🍳 Готовится @break
                                    @case('DELIVERED') ✅ Выдан @break
                                    @case('CANCELLED') ❌ Отменён @break
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label>Точка выдачи:</label>
                    <select name="pickup_point_id">
                        <option value="">Все</option>
                        @foreach($pickupPoints as $point)
                            <option value="{{ $point->point_id }}" {{ request('pickup_point_id') == $point->point_id ? 'selected' : '' }}>
                                {{ $point->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-filter">🔍 Применить</button>
                <a href="{{ route('manager.orders') }}" class="btn-reset">🔄 Сбросить</a>
            </form>
        </div>

        <!-- Список заказов -->
        @forelse($orders as $order)
            <div class="order-card-manager">
                <div class="order-header-manager">
                    <div class="order-id">
                        Заказ #{{ $order->order_id }}
                        <span class="order-date">{{ date('d.m.Y H:i', strtotime($order->order_date)) }}</span>
                    </div>
                    <div class="order-status-manager status-{{ strtolower($order->status) }}">
                        @switch($order->status)
                            @case('NEW') 🆕 Новый @break
                            @case('COOKING') 🍳 Готовится @break
                            @case('DELIVERED') ✅ Выдан @break
                            @case('CANCELLED') ❌ Отменён @break
                        @endswitch
                    </div>
                </div>

                <div class="order-info">
                    <div class="info-row">
                        <span class="label">👤 Клиент:</span>
                        <span>{{ $order->user->last_name }} {{ $order->user->first_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">📞 Телефон:</span>
                        <span>{{ $order->user->phone }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">📍 ПВЗ:</span>
                        <span>{{ $order->pickupPoint->address ?? 'Не указан' }}</span>
                    </div>
                </div>

                <div class="order-items-preview">
                    <strong>📦 Состав заказа:</strong>
                    <ul>
                        @foreach($order->items as $item)
                            <li>{{ $item->product_name }} x{{ $item->quantity }} = {{ number_format($item->price_per_item * $item->quantity, 0, ',', ' ') }} ₽</li>
                        @endforeach
                    </ul>
                </div>

                <div class="order-summary-manager">
                    <div class="total">💰 Итого: {{ number_format($order->final_sum, 0, ',', ' ') }} ₽</div>
                    <div class="bonus-info">
                        @if($order->used_bonus_points > 0)
                            🎁 Списано: -{{ number_format($order->used_bonus_points, 0, ',', ' ') }} баллов
                        @endif
                        @if($order->earned_bonus_points > 0)
                            🎁 Начислено: +{{ number_format($order->earned_bonus_points, 0, ',', ' ') }} баллов
                        @endif
                    </div>
                </div>

                <!-- Форма смены статуса -->
                <form method="POST" action="{{ route('manager.updateStatus', $order->order_id) }}" class="status-form">
                    @csrf
                    @method('PATCH')
                    <div class="status-control">
                        <select name="status" class="status-select">
                            <option value="NEW" {{ $order->status == 'NEW' ? 'selected' : '' }}>🆕 Новый</option>
                            <option value="COOKING" {{ $order->status == 'COOKING' ? 'selected' : '' }}>🍳 Готовится</option>
                            <option value="DELIVERED" {{ $order->status == 'DELIVERED' ? 'selected' : '' }}>✅ Выдан</option>
                            <option value="CANCELLED" {{ $order->status == 'CANCELLED' ? 'selected' : '' }}>❌ Отменён</option>
                        </select>
                        <button type="submit" class="btn-update-status">Обновить статус</button>
                    </div>
                </form>
            </div>
        @empty
            <div class="empty-orders-manager">
                <p>📭 Заказов не найдено</p>
            </div>
        @endforelse

        <!-- Пагинация -->
        <div class="pagination-manager">
            {{ $orders->links() }}
        </div>
    </div>

    <style>
        .filters-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .filters-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-group label {
            font-size: 14px;
            font-weight: bold;
        }

        .filter-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            min-width: 150px;
        }

        .btn-filter, .btn-reset {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-filter {
            background: #FF6B00;
            color: white;
        }

        .btn-reset {
            background: #666;
            color: white;
        }

        .order-card-manager {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .order-header-manager {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-id {
            font-size: 18px;
            font-weight: bold;
        }

        .order-date {
            font-size: 12px;
            color: #888;
            margin-left: 10px;
            font-weight: normal;
        }

        .order-status-manager {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-new { background: #e3f2fd; color: #1976d2; }
        .status-cooking { background: #fff3e0; color: #ff9800; }
        .status-delivered { background: #e8f5e9; color: #4caf50; }
        .status-cancelled { background: #ffebee; color: #f44336; }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 10px;
        }

        .info-row .label {
            font-weight: bold;
            color: #555;
            margin-right: 10px;
        }

        .order-items-preview {
            margin-bottom: 15px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 10px;
        }

        .order-items-preview ul {
            margin: 10px 0 0 20px;
        }

        .order-summary-manager {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 10px 0;
            margin-bottom: 15px;
            border-top: 1px solid #eee;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            color: #FF6B00;
        }

        .bonus-info {
            font-size: 14px;
            color: #666;
        }

        .status-control {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .status-select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-update-status {
            background: #2196F3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-update-status:hover {
            background: #1976D2;
        }

        .empty-orders-manager {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 15px;
        }

        .pagination-manager {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .filters-form {
                flex-direction: column;
            }

            .filter-group select {
                width: 100%;
            }

            .order-summary-manager {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection

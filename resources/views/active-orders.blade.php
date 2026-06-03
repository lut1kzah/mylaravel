@extends('layouts.app')

@section('title', 'Мои активные заказы')

@section('content')
    <div class="active-orders-container">
        <h1 class="page-title">Мои активные заказы</h1>

        @if($activeOrders->isEmpty())
            <div class="empty-orders-card">
                <div class="empty-icon">🍔</div>
                <p>У вас нет активных заказов</p>
                <p class="empty-subtitle">Все заказы выполнены или отменены</p>
                <a href="{{ route('menu') }}" class="btn-orange">Перейти в меню</a>
            </div>
        @else
            @foreach($activeOrders as $order)
                <div class="active-order-card">
                    <div class="order-header">
                        <div class="order-info-left">
                            <span class="order-number">Заказ #{{ $order->order_id }}</span>
                            <span class="order-date">{{ date('d.m.Y H:i', strtotime($order->order_date)) }}</span>
                        </div>
                        <div class="order-status-badge status-{{ strtolower($order->status) }}">
                            @if($order->status == 'NEW')
                                🆕 Новый
                            @elseif($order->status == 'COOKING')
                                🍳 Готовится
                            @endif
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-items-list">
                            <strong>📋 Состав заказа:</strong>
                            <table class="order-items-table">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="item-name">{{ $item->product_name }}</td>
                                        <td class="item-quantity">x{{ $item->quantity }}</td>
                                        <td class="item-price">{{ number_format($item->price_per_item * $item->quantity, 0, ',', ' ') }} ₽</td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="order-total-row">
                                <span>Итого:</span>
                                <strong>{{ number_format($order->final_sum, 0, ',', ' ') }} ₽</strong>
                            </div>
                        </div>

                        <div class="order-details">
                            <div class="detail-row">
                                <span class="detail-label">📍 Точка самовывоза:</span>
                                <span class="detail-value">{{ $order->pickupPoint->address ?? 'Не указан' }}</span>
                            </div>
                            @if($order->used_bonus_points > 0)
                                <div class="detail-row">
                                    <span class="detail-label">🎁 Списано бонусов:</span>
                                    <span class="detail-value bonus-spent">-{{ number_format($order->used_bonus_points, 0, ',', ' ') }} ₽</span>
                                </div>
                            @endif
                        </div>

                        @if($order->status == 'NEW')
                            <div class="order-status-message status-new-message">
                                <span class="message-icon">⏳</span>
                                <span>Заказ принят и ожидает приготовления</span>
                            </div>
                        @elseif($order->status == 'COOKING')
                            <div class="order-status-message status-cooking-message">
                                <span class="message-icon">🔥</span>
                                <span>Ваш заказ уже готовится! Скоро можно будет забрать.</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <style>
        .active-orders-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            font-size: 32px;
            color: white;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* Пустая корзина */
        .empty-orders-card {
            background: white;
            border-radius: 20px;
            padding: 50px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .empty-orders-card p {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-subtitle {
            font-size: 14px !important;
            color: #888 !important;
            margin-bottom: 30px !important;
        }

        .btn-orange {
            display: inline-block;
            background: #FF6B00;
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-orange:hover {
            background: #e05e00;
            transform: translateY(-2px);
        }

        /* Карточка заказа */
        .active-order-card {
            background: white;
            border-radius: 20px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s;
        }

        .active-order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        /* Шапка заказа */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 25px;
            background: #FF6B00;
            color: white;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-number {
            font-size: 20px;
            font-weight: bold;
        }

        .order-date {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            margin-left: 15px;
        }

        .order-status-badge {
            padding: 5px 18px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-new {
            background: #2196F3;
            color: white;
        }

        .status-cooking {
            background: #ff9800;
            color: white;
        }

        /* Тело заказа */
        .order-body {
            padding: 20px 25px;
        }

        .order-items-list {
            margin-bottom: 20px;
        }

        .order-items-list strong {
            display: block;
            margin-bottom: 12px;
            color: #333;
            font-size: 16px;
        }

        .order-items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-items-table tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .order-items-table td {
            padding: 10px 0;
        }

        .item-name {
            text-align: left;
            font-weight: 500;
        }

        .item-quantity {
            text-align: center;
            width: 80px;
            color: #888;
        }

        .item-price {
            text-align: right;
            width: 100px;
            font-weight: 500;
            color: #FF6B00;
        }

        .order-total-row {
            text-align: right;
            padding-top: 12px;
            margin-top: 8px;
            border-top: 2px solid #FF6B00;
            font-size: 18px;
        }

        .order-total-row strong {
            color: #FF6B00;
            margin-left: 10px;
            font-size: 20px;
        }

        /* Детали заказа */
        .order-details {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-row:last-child {
            border-bottom: none;
        }


        .detail-value {
            color: #1a1a1a;
        }

        .bonus-spent {
            color: #e74c3c;
        }

        /* Сообщения о статусе */
        .order-status-message {
            padding: 14px;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .status-new-message {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-cooking-message {
            background: #fff3e0;
            color: #e65100;
        }

        .message-icon {
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .active-orders-container {
                padding: 15px;
            }

            .order-header {
                flex-direction: column;
                text-align: center;
            }

            .order-date {
                margin-left: 0;
                display: block;
                margin-top: 5px;
            }

            .detail-row {
                flex-direction: column;
                gap: 5px;
            }

            .order-total-row {
                text-align: center;
            }

            .page-title {
                font-size: 24px;
            }
        }
    </style>
@endsection

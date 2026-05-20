@extends('layouts.app')

@section('title', 'История заказов')

@section('content')
    <div class="profile-container">
        <div class="profile-card">
            <h2>📜 История заказов</h2>

            @if($orders->isEmpty())
                <div class="empty-orders">
                    <p>У вас пока нет заказов</p>
                    <a href="{{ route('menu') }}" class="btn">Перейти в меню</a>
                </div>
            @else
                @foreach($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <strong>Заказ #{{ $order->order_id }}</strong>
                                <span class="order-date">{{ date('d.m.Y H:i', strtotime($order->order_date)) }}</span>
                            </div>
                            <div class="order-status status-{{ strtolower($order->status) }}">
                                @switch($order->status)
                                    @case('NEW') Новый @break
                                    @case('COOKING') Готовится @break
                                    @case('DELIVERED') Выдан @break
                                    @case('CANCELLED') Отменён @break
                                    @default {{ $order->status }}
                                @endswitch
                            </div>
                        </div>

                        <div class="order-items">
                            @foreach($order->items as $item)
                                <div class="order-item">
                                    <span class="order-item-name">{{ $item->product_name }}</span>
                                    <span class="order-item-quantity">x{{ $item->quantity }}</span>
                                    <span class="order-item-price">{{ number_format($item->price_per_item * $item->quantity, 0, ',', ' ') }} ₽</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-footer">
                            <div class="order-summary">
                                <span>Сумма: {{ number_format($order->total_original_sum, 0, ',', ' ') }} ₽</span>
                                @if($order->discount_sum > 0)
                                    <span>Скидка: -{{ number_format($order->discount_sum, 0, ',', ' ') }} ₽</span>
                                @endif
                                @if($order->used_bonus_points > 0)
                                    <span>Списано бонусов: -{{ number_format($order->used_bonus_points, 0, ',', ' ') }} ₽</span>
                                @endif
                                <span class="order-total">Итого: {{ number_format($order->final_sum, 0, ',', ' ') }} ₽</span>
                            </div>
                            @if($order->earned_bonus_points > 0)
                                <div class="order-bonus">
                                    +{{ number_format($order->earned_bonus_points, 0, ',', ' ') }} бонусов начислено
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="profile-back">
                <a href="{{ route('profile') }}" class="btn">← Вернуться в профиль</a>
            </div>
        </div>
    </div>
@endsection

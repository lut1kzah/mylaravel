@extends('layouts.app')

@section('title', 'Заказ оформлен')

@section('content')
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">✅</div>
            <h1>Заказ успешно оформлен!</h1>
            <p>Номер вашего заказа: <strong>#{{ $order->order_id }}</strong></p>
            <p>Сумма к оплате: <strong>{{ number_format($order->final_sum, 0, ',', ' ') }} ₽</strong></p>
            <p>Ожидайте сообщение о готовности заказа.</p>
            <p>Способ получения: <strong>Самовывоз</strong></p>

            <!-- Важное предупреждение -->
            <div class="screenshot-warning">
                <div class="warning-icon">📸</div>
                <p class="warning-text">
                    <strong>Важно!</strong> Пожалуйста, сделайте скриншот данного экрана
                    и покажите его на кассе для подтверждения заказа.
                </p>
            </div>

            <div class="success-actions">
                <a href="/" class="btn">На главную</a>
                <a href="{{ route('profile') }}" class="btn">В профиль</a>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
    <div class="checkout-container">
        <h1>Оформление заказа</h1>

        <div class="checkout-grid">
            <!-- Левая колонка - товары -->
            <div class="checkout-items">
                <h3>Ваш заказ</h3>
                @foreach($cartItems as $item)
                    <div class="checkout-item">
                        <div class="checkout-item-info">
                            <span class="checkout-item-name">{{ $item->product->name }}</span>
                            <span class="checkout-item-quantity">x{{ $item->quantity }}</span>
                        </div>
                        <div class="checkout-item-price">
                            {{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} ₽
                        </div>
                    </div>
                @endforeach

                <div class="checkout-divider"></div>

                <div class="checkout-subtotal">
                    <span>Сумма:</span>
                    <span>{{ number_format($subtotal, 0, ',', ' ') }} ₽</span>
                </div>

                @if(session('discount_amount'))
                    <div class="checkout-discount">
                        <span>Скидка по промокоду:</span>
                        <span>- {{ number_format(session('discount_amount'), 0, ',', ' ') }} ₽</span>
                    </div>
                @endif

                @if(session('bonus_used'))
                    <div class="checkout-bonus">
                        <span>Списано бонусов:</span>
                        <span>- {{ number_format(session('bonus_used'), 0, ',', ' ') }} ₽</span>
                    </div>
                @endif

                <div class="checkout-total">
                    <span>Итого к оплате:</span>
                    <span class="total-amount">{{ number_format($finalSum, 0, ',', ' ') }} ₽</span>
                </div>
            </div>

            <!-- Правая колонка - форма -->
            <div class="checkout-form">
                <!-- Форма для промокода -->
                <form method="POST" action="{{ route('checkout.applyPromocode') }}" class="discount-form">
                    @csrf
                    <div class="form-group">
                        <label>Промокод</label>
                        <div class="promocode-wrapper">
                            <input type="text" name="promocode" placeholder="Введите промокод" class="promocode-input">
                            <button type="submit" class="apply-btn">Применить</button>
                        </div>
                    </div>
                </form>

                <!-- Форма для бонусов -->
                <form method="POST" action="{{ route('checkout.applyBonus') }}" class="discount-form" id="bonusForm">
                    @csrf
                    <div class="form-group">
                        <label>Бонусы (до {{ $maxBonusPercent }}% от суммы заказа)</label>
                        <input type="number" name="bonus_points" id="bonusPointsInput" class="bonus-input"
                               placeholder="Введите сумму бонусов" min="0" max="{{ $maxBonusPoints }}"
                               step="1" value="0">
                        <small>Доступно: {{ number_format($bonusBalance, 0, ',', ' ') }} баллов |
                            Максимум {{ $maxBonusPercent }}% от заказа ({{ number_format($maxBonusPoints, 0, ',', ' ') }} баллов)</small>
                        <button type="submit" class="apply-bonus-btn">Применить бонусы</button>
                    </div>
                </form>

                <!-- Основная форма оформления -->
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Способ оплаты</label>
                        <select name="payment_method" class="payment-select">
                            <option value="cash">Наличными при получении</option>
                            <option value="card">Картой при получении</option>
                        </select>
                    </div>

                    <button type="submit" class="submit-order-btn">Подтвердить заказ</button>
                </form>
            </div>
        </div>
    </div>
@endsection

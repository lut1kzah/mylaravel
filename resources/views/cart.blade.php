@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
    <div class="cart-container">
        <h1 class="cart-title">🛒 Корзина</h1>

        @if($cartItems->isEmpty())
            <div class="empty-cart">
                <div class="empty-cart-icon">🛍️</div>
                <p>Ваша корзина пуста</p>
                <a href="{{ route('menu') }}" class="btn">Перейти в меню</a>
            </div>
        @else
            <div class="cart-items">
                <!-- Заголовки для десктопа -->
                <div class="cart-header">
                    <div>Товар</div>
                    <div>Цена</div>
                    <div>Количество</div>
                    <div>Сумма</div>
                    <div></div>
                </div>

                @foreach($cartItems as $item)
                    <div class="cart-item" data-id="{{ $item->id }}">
                        <div class="cart-item-product">
                            <div class="cart-item-image">
                                @if($item->product->image_url)
                                    <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}">
                                @else
                                    🍔
                                @endif
                            </div>
                            <div class="cart-item-info">
                                <h3 class="cart-item-name">{{ $item->product->name }}</h3>
                                @if($item->product->weight)
                                    <span class="cart-item-weight">{{ $item->product->weight }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="cart-item-price">
                            {{ number_format($item->product->price, 0, ',', ' ') }} ₽
                        </div>

                        <div class="cart-item-quantity">
                            <form method="POST" action="{{ route('cart.update', $item->id) }}" class="quantity-form">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="qty-btn minus">-</button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="quantity-input">
                                <button type="button" class="qty-btn plus">+</button>
                            </form>
                        </div>

                        <div class="cart-item-total">
                            {{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} ₽
                        </div>

                        <div class="cart-item-remove">
                            <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn" title="Удалить">🗑️</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <div class="cart-total">
                    <span>Итого к оплате:</span>
                    <span class="total-amount">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                </div>
                <div class="cart-actions">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="clear-cart-btn">Очистить корзину</button>
                    </form>
                    <a href="{{ route('menu') }}" class="continue-btn">Продолжить покупки</a>
                    <a href="{{ route('checkout') }}" class="checkout-btn">Оформить заказ</a>
                </div>
            </div>
        @endif
    </div>
@endsection

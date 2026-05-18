@extends('layouts.app')

@section('title', 'Меню')

@section('content')
    <div class="menu-wrapper">
        <!-- Левое меню с категориями -->
        <aside class="menu-sidebar">
            <h3>Категории</h3>
            <ul class="category-list">
                @foreach($categories as $category)
                    <li>
                        <a href="#category-{{ $category->category_id }}">
                            {{ $category->category_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- Основной контент с товарами -->
        <main class="menu-main">
            @foreach($categories as $category)
                <div id="category-{{ $category->category_id }}" class="category-section">
                    <h2 class="category-title">{{ $category->category_name }}</h2>

                    <!-- Сетка товаров - автоматический перенос -->
                    <div class="products-grid">
                        @forelse($category->products as $product)
                            <div class="product-card">
                                <div class="product-image {{ $category->category_name == 'Напитки' ? 'drink-image' : '' }}">
                                    @if($product->image_url)
                                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">
                                    @else
                                        @if($category->category_name == 'Шаурма')
                                            🥙
                                        @elseif($category->category_name == 'Снеки')
                                            🍟
                                        @else
                                            🥤
                                        @endif
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                    <p class="product-desc">{{ Str::limit($product->description, 200) }}</p>
                                    <div class="product-price-line">
                                        <span class="product-price">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                                        @if($product->weight)
                                            <span class="product-weight">{{ $product->weight }}</span>
                                        @endif
                                    </div>
                                    <button class="add-to-cart" onclick="alert('Корзина скоро будет готова 📦')">Добавить</button>
                                </div>
                            </div>
                        @empty
                            <div class="product-card">
                                <div class="product-info">
                                    <p class="product-desc">Товары скоро появятся</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </main>
    </div>
@endsection

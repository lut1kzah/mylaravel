@extends('admin.layouts.admin')

@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1>📦 Управление товарами</h1>
            <a href="{{ route('admin.products.create') }}" class="btn">+ Добавить товар</a>
        </div>

        <!-- Фильтры -->
        <div style="background: white; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 20px;">
                    <div style="flex: 2;">
                        <input type="text" name="search" placeholder="Поиск по названию..." value="{{ request('search') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                    <div>
                        <select name="category_id" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}" {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="is_available" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                            <option value="">Все</option>
                            <option value="1" {{ request('is_available') == '1' ? 'selected' : '' }}>✅ Доступен</option>
                            <option value="0" {{ request('is_available') == '0' ? 'selected' : '' }}>❌ Скрыт</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn" style="padding: 10px 30px;">🔍 Найти</button>
                    <a href="{{ route('admin.products.index') }}" class="btn-reset">🔄 Сбросить</a>
                </div>
            </form>
        </div>

        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Изображение</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Вес</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->product_id }}</td>
                    <td>
                        @if($product->image_url)
                            <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">📷</div>
                        @endif
                    </td>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td>{{ $product->category->category_name }}</td>
                    <td>{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                    <td>{{ $product->weight ?? '-' }}</td>
                    <td>
                        @if($product->is_available)
                            <span class="badge-active">✅ Доступен</span>
                        @else
                            <span class="badge-inactive">❌ Скрыт</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('admin.products.edit', $product->product_id) }}" class="btn-icon" title="Редактировать">✏️</a>
                            <form action="{{ route('admin.products.toggle', $product->product_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-icon {{ $product->is_available ? 'btn-icon-warning' : 'btn-icon-success' }}" title="{{ $product->is_available ? 'Скрыть' : 'Показать' }}">
                                    {{ $product->is_available ? '👁️‍🗨️' : '👁️' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Товары не найдены</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $products->links() }}
        </div>
    </div>
@endsection

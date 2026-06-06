@extends('admin.layouts.admin')

@section('content')
    <div>
        <h1>✏️ Редактирование товара: {{ $product->name }}</h1>

        <form method="POST" action="{{ route('admin.products.update', $product->product_id) }}" enctype="multipart/form-data" style="background: white; padding: 25px; border-radius: 15px; margin-top: 20px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Название *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
                <label>Категория *</label>
                <select name="id_category" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ $product->id_category == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Цена *</label>
                    <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="form-group">
                    <label>Вес</label>
                    <input type="text" name="weight" value="{{ old('weight', $product->weight) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Текущее изображение</label>
                @if($product->image_url)
                    <div>
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                    </div>
                @else
                    <div>Нет изображения</div>
                @endif
                <input type="file" name="image" accept="image/*" style="margin-top: 10px;">
            </div>


            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" class="btn">Сохранить</button>
                <a href="{{ route('admin.products.index') }}" class="btn" style="background: #666;">Отмена</a>
            </div>
        </form>
    </div>
@endsection

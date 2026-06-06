@extends('admin.layouts.admin')

@section('content')
    <div>
        <h1>➕ Добавление товара</h1>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" style="background: white; padding: 25px; border-radius: 15px; margin-top: 20px;">
            @csrf

            <div class="form-group">
                <label>Название *</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>Категория *</label>
                <select name="id_category" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Цена *</label>
                    <input type="number" name="price" step="0.01" value="{{ old('price') }}" required>
                </div>
                <div class="form-group">
                    <label>Вес</label>
                    <input type="text" name="weight" placeholder="300 г" value="{{ old('weight') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Изображение</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" class="btn">Сохранить</button>
                <a href="{{ route('admin.products.index') }}" class="btn" style="background: #666;">Отмена</a>
            </div>
        </form>
    </div>
@endsection

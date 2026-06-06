@extends('admin.layouts.admin')

@section('content')
    <div>
        <h1>➕ Добавление точки самовывоза</h1>

        <form method="POST" action="{{ route('admin.pickup-points.store') }}" style="background: white; padding: 25px; border-radius: 15px; margin-top: 20px;">
            @csrf

            <div class="form-group">
                <label>Адрес *</label>
                <input type="text" name="address" value="{{ old('address') }}" required placeholder="ул. Примерная, д. 1">
            </div>

            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+7 (999) 123-45-67">
            </div>

            <div class="form-group">
                <label>Часы работы *</label>
                <input type="text" name="working_hours" value="{{ old('working_hours') }}" required placeholder="10:00 - 22:00">
            </div>

            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" class="btn">Сохранить</button>
                <a href="{{ route('admin.pickup-points.index') }}" class="btn" style="background: #666;">Отмена</a>
            </div>
        </form>
    </div>
@endsection

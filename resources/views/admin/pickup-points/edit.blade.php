@extends('admin.layouts.admin')

@section('content')
    <div>
        <h1>✏️ Редактирование ПВЗ</h1>

        <form method="POST" action="{{ route('admin.pickup-points.update', $pickupPoint->point_id) }}" style="background: white; padding: 25px; border-radius: 15px; margin-top: 20px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Адрес *</label>
                <input type="text" name="address" value="{{ old('address', $pickupPoint->address) }}" required>
            </div>

            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" value="{{ old('phone', $pickupPoint->phone) }}" required>
            </div>

            <div class="form-group">
                <label>Часы работы *</label>
                <input type="text" name="working_hours" value="{{ old('working_hours', $pickupPoint->working_hours) }}" required>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" class="btn">Сохранить</button>
                <a href="{{ route('admin.pickup-points.index') }}" class="btn" style="background: #666;">Отмена</a>
            </div>
        </form>
    </div>
@endsection

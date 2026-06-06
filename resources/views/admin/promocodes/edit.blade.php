@extends('admin.layouts.admin')

@section('content')
    <div>
        <h1>✏️ Редактирование промокода: {{ $promocode->code }}</h1>

        <form method="POST" action="{{ route('admin.promocodes.update', $promocode->promocode_id) }}" style="background: white; padding: 25px; border-radius: 15px; margin-top: 20px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Код промокода *</label>
                <input type="text" name="code" value="{{ old('code', $promocode->code) }}" required>
            </div>

            <div class="form-group">
                <label>Скидка (%) *</label>
                <input type="number" name="discount_percent" value="{{ old('discount_percent', $promocode->discount_percent) }}" required min="1" max="100">
            </div>

            <div class="form-group">
                <label>Минимальная сумма заказа</label>
                <input type="number" name="min_order_sum" value="{{ old('min_order_sum', $promocode->min_order_sum) }}" step="0.01" min="0">
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Действует с</label>
                    <input type="datetime-local" name="valid_from" value="{{ old('valid_from', $promocode->valid_from ? date('Y-m-d\TH:i', strtotime($promocode->valid_from)) : '') }}">
                </div>
                <div class="form-group">
                    <label>Действует до</label>
                    <input type="datetime-local" name="valid_until" value="{{ old('valid_until', $promocode->valid_until ? date('Y-m-d\TH:i', strtotime($promocode->valid_until)) : '') }}">
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" class="btn">Сохранить</button>
                <a href="{{ route('admin.promocodes.index') }}" class="btn" style="background: #666;">Отмена</a>
            </div>
        </form>
    </div>
@endsection

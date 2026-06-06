@extends('admin.layouts.admin')

@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1>🎫 Управление промокодами</h1>
            <a href="{{ route('admin.promocodes.create') }}" class="btn">+ Создать промокод</a>
        </div>

        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Код</th>
                <th>Скидка</th>
                <th>Мин. сумма</th>
                <th>Действует с</th>
                <th>Действует до</th>
                <th>Использований</th>
                <th>Сумма скидки</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($promocodes as $promocode)
                <tr>
                    <td>{{ $promocode->promocode_id }}</td>
                    <td><strong>{{ $promocode->code }}</strong></td>
                    <td>{{ $promocode->discount_percent }}%</td>
                    <td>{{ number_format($promocode->min_order_sum, 0, ',', ' ') }} ₽</td>
                    <td>{{ $promocode->valid_from ? date('d.m.Y', strtotime($promocode->valid_from)) : '∞' }}</td>
                    <td>{{ $promocode->valid_until ? date('d.m.Y', strtotime($promocode->valid_until)) : '∞' }}</td>
                    <td>{{ $promocode->usage_count ?? 0 }}</td>
                    <td>{{ number_format($promocode->total_discount ?? 0, 0, ',', ' ') }} ₽</td>
                    <td>
                        @if($promocode->is_active)
                            <span class="badge-active">Активен</span>
                        @else
                            <span class="badge-inactive">Неактивен</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        <div style="display: flex; gap: 5px; align-items: center;">
                            <a href="{{ route('admin.promocodes.edit', $promocode->promocode_id) }}" class="btn-icon" title="Редактировать">✏️</a>
                            <a href="{{ route('admin.promocodes.stats', $promocode->promocode_id) }}" class="btn-icon btn-icon-stats" title="Статистика">📊</a>
                            <form action="{{ route('admin.promocodes.toggle', $promocode->promocode_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-icon {{ $promocode->is_active ? 'btn-icon-danger' : 'btn-icon-success' }}" title="{{ $promocode->is_active ? 'Деактивировать' : 'Активировать' }}">
                                    {{ $promocode->is_active ? '❌' : '✅' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Промокодов пока нет</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $promocodes->links() }}
        </div>
    </div>
@endsection

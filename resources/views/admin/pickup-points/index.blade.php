@extends('admin.layouts.admin')

@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1>📍 Управление точками самовывоза</h1>
            <a href="{{ route('admin.pickup-points.create') }}" class="btn">+ Добавить ПВЗ</a>
        </div>

        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Адрес</th>
                <th>Телефон</th>
                <th>Часы работы</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($pickupPoints as $point)
                <tr>
                    <td>{{ $point->point_id }}</td>
                    <td><strong>{{ $point->address }}</strong></td>
                    <td>{{ $point->phone }}</td>
                    <td>{{ $point->working_hours }}</td>
                    <td>{{ date('d.m.Y', strtotime($point->created_at)) }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('admin.pickup-points.edit', $point->point_id) }}" class="btn-icon" title="Редактировать">✏️</a>
                            <form action="{{ route('admin.pickup-points.destroy', $point->point_id) }}" method="POST" onsubmit="return confirm('Удалить точку {{ $point->address }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" title="Удалить">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Точки самовывоза не добавлены</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $pickupPoints->links() }}
        </div>
    </div>
@endsection

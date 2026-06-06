@extends('admin.layouts.admin')

@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1>👥 Управление пользователями</h1>
        </div>

        <!-- Фильтры и поиск -->
        <div style="background: white; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <!-- Первая строка: поиск и фильтр -->
                <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 20px;">
                    <!-- Поле поиска -->
                    <div style="flex: 3; min-width: 250px;">
                        <input type="text" name="search" placeholder="По имени, телефону, email..." value="{{ request('search') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    </div>

                    <!-- Фильтр по роли -->
                    <div style="min-width: 180px;">
                        <select name="role_id" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer;">
                            <option value="">Все роли</option>
                            <option value="1" {{ request('role_id') == 1 ? 'selected' : '' }}>👤 Пользователь</option>
                            <option value="2" {{ request('role_id') == 2 ? 'selected' : '' }}>👨‍💼 Менеджер</option>
                            <option value="3" {{ request('role_id') == 3 ? 'selected' : '' }}>👑 Администратор</option>
                        </select>
                    </div>
                </div>

                <!-- Вторая строка: кнопки -->
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="submit" class="btn" style="padding: 12px 35px; font-size: 16px; font-weight: bold; background: #FF6B00;">🔍 Найти</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-reset" style="padding: 8px 20px; background: #95a5a6; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; transition: all 0.3s;">🔄 Сбросить</a>
                </div>
            </form>
        </div>

        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>ФИО</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Бонусы</th>
                <th>Уровень</th>
                <th>Потрачено</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->user_id }}</td>
                    <td>
                        {{ $user->last_name }} {{ $user->first_name }}
                        @if($user->middle_name) {{ $user->middle_name }} @endif
                    </td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role_id == 1)
                            <span class="badge-active">Пользователь</span>
                        @elseif($user->role_id == 2)
                            <span class="badge-active" style="background: #f39c12;">Менеджер</span>
                        @else
                            <span class="badge-active" style="background: #e74c3c;">Админ</span>
                        @endif
                    </td>
                    <td>{{ number_format($user->bonus_balance, 0, ',', ' ') }}</td>
                    <td style="color: #FF6B00; font-weight: bold;">{{ $user->current_level }}</td>
                    <td>{{ number_format($user->total_spent, 0, ',', ' ') }} ₽</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('admin.users.show', $user->user_id) }}" class="btn-icon" title="Просмотр">👁️</a>
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn-icon" title="Редактировать роль">✏️</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Пользователи не найдены</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    </div>
@endsection

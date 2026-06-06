@extends('admin.layouts.admin')

@section('content')
    <div>
        <h1>✏️ Редактирование роли пользователя</h1>

        <div style="background: white; padding: 25px; border-radius: 15px; margin-top: 20px;">
            <p><strong>Пользователь:</strong> {{ $user->last_name }} {{ $user->first_name }} ({{ $user->email }})</p>

            <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Роль</label>
                    <select name="role_id" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px;">
                        @foreach($roles as $id => $name)
                            <option value="{{ $id }}" {{ $user->role_id == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn">Сохранить</button>
                    <a href="{{ route('admin.users.index') }}" class="btn" style="background: #666;">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Показать форму регистрации
    public function showForm()
    {
        return view('register');
    }

    // Обработка отправки формы
    public function register(Request $request)
    {
        // Валидация данных
        $validated = $request->validate([
            'phone' => 'required|string|max:15|unique:Users,phone',
            'email' => 'required|email|max:100|unique:Users,email',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'gender' => 'required|in:0,1',
            'password' => 'required|min:6|confirmed',
        ]);

        // Создание пользователя
        User::create([
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'gender' => $validated['gender'],  // добавить эту строку
            'bonus_balance' => 0,
            'current_level' => 'BRONZE',
            'total_spent' => 0,
            'last_3_months_orders_count' => 0,
        ]);

        // Перенаправление на страницу входа с сообщением об успехе
        return redirect('/login')->with('success', 'Регистрация успешна! Теперь войдите в систему.');
    }
}

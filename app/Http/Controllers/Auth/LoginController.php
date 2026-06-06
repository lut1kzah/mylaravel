<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Редирект в зависимости от роли
            if ($user->role_id == 3) {
                // Администратор -> в админ-панель
                return redirect()->route('admin.promocodes.index')->with('success', 'Добро пожаловать в админ-панель!');
            }

            if ($user->role_id == 2) {
                // Менеджер -> в панель управления заказами
                return redirect()->route('manager.orders')->with('success', 'Добро пожаловать в панель управления!');
            }

            // Обычный пользователь -> в профиль
            return redirect()->route('profile')->with('success', 'Добро пожаловать!');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}

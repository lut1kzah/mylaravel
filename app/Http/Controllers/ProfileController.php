<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Services\LevelService;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $levelService = new LevelService();

        // 1. Обновляем количество заказов за последние 3 месяца
        $levelService->updateOrdersCount($user);

        // 2. Пересчитываем и обновляем уровень пользователя
        $newLevel = $levelService->calculateLevel($user);
        if ($user->current_level != $newLevel) {
            $oldLevel = $user->current_level;
            $user->current_level = $newLevel;
            $user->save();

            // Добавляем сообщение о повышении уровня (опционально)
            session()->flash('level_up', "Поздравляем! Ваш уровень повышен с {$oldLevel} до {$newLevel}!");
        }

        // Информация о следующем уровне
        $nextLevel = $levelService->getNextLevelInfo($user);

        // Кэшбэк текущего уровня
        $cashback = $levelService->getCashback($user->current_level);

        // Правила всех уровней
        $levelsRules = $levelService->getLevelsRules();

        return view('profile', compact('user', 'nextLevel', 'cashback', 'levelsRules'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->user_id)
            ->orderBy('order_date', 'desc')
            ->with('items.product')
            ->get();

        return view('profile-orders', compact('user', 'orders'));
    }

    // Обновление профиля
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'phone' => 'required|string|max:15|unique:Users,phone,' . $user->user_id . ',user_id',
            'email' => 'required|email|max:100|unique:Users,email,' . $user->user_id . ',user_id',
        ]);

        $user->update([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('profile')->with('success', 'Профиль успешно обновлён!');
    }

    // Форма редактирования профиля
    public function edit()
    {
        $user = Auth::user();
        return view('profile-edit', compact('user'));
    }
    // Активные заказы (только NEW и COOKING)
    public function activeOrders()
    {
        $user = Auth::user();

        $activeOrders = Order::where('user_id', $user->user_id)
            ->whereIn('status', ['NEW', 'COOKING'])
            ->orderBy('order_date', 'desc')
            ->with('items.product', 'pickupPoint')
            ->get();

        return view('active-orders', compact('user', 'activeOrders'));
    }
}

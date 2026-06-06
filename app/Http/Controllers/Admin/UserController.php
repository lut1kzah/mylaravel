<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\BonusTransaction;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private function checkAdmin()
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 3) {
            abort(403, 'Доступ запрещён. Только для администраторов.');
        }
    }

    // Список всех пользователей
    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = User::with('pickupPoint');

        // Поиск по имени или телефону
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Фильтр по роли
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $users = $query->orderBy('user_id', 'desc')->paginate(20);

        $roles = [
            1 => 'Пользователь',
            2 => 'Менеджер',
            3 => 'Администратор',
        ];

        return view('admin.users.index', compact('users', 'roles'));
    }

    // Просмотр пользователя
    public function show($id)
    {
        $this->checkAdmin();
        $user = User::with('pickupPoint')->findOrFail($id);

        // Статистика заказов
        $ordersCount = Order::where('user_id', $user->user_id)->count();
        $totalSpent = Order::where('user_id', $user->user_id)->sum('final_sum');
        $completedOrders = Order::where('user_id', $user->user_id)->where('status', 'DELIVERED')->count();

        // Бонусные транзакции
        $bonusTransactions = BonusTransaction::where('id_user', $user->user_id)
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $bonusEarned = BonusTransaction::where('id_user', $user->user_id)->where('type', 'EARNED')->sum('amount');
        $bonusSpent = abs(BonusTransaction::where('id_user', $user->user_id)->where('type', 'SPENT')->sum('amount'));

        $lastOrders = Order::where('user_id', $user->user_id)
            ->orderBy('order_date', 'desc')
            ->limit(5)
            ->get();

        return view('admin.users.show', compact('user', 'ordersCount', 'totalSpent', 'completedOrders', 'bonusTransactions', 'bonusEarned', 'bonusSpent', 'lastOrders'));
    }

    // Форма редактирования роли
    public function edit($id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);
        $roles = [
            1 => 'Пользователь',
            2 => 'Менеджер',
            3 => 'Администратор',
        ];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Обновление роли пользователя
    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $user = User::findOrFail($id);

        $request->validate([
            'role_id' => 'required|in:1,2,3',
        ]);

        $oldRole = $user->role_id;
        $user->role_id = $request->role_id;
        $user->save();

        $roleNames = [1 => 'Пользователь', 2 => 'Менеджер', 3 => 'Администратор'];

        return redirect()->route('admin.users.index')->with('success', "Роль пользователя {$user->last_name} {$user->first_name} изменена с '{$roleNames[$oldRole]}' на '{$roleNames[$request->role_id]}'");
    }

    // Корректировка бонусного баланса
    public function adjustBonus(Request $request, $id)
    {
        $this->checkAdmin();

        $user = User::findOrFail($id);

        $request->validate([
            'amount' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        $amount = $request->amount;
        $user->bonus_balance += $amount;
        $user->save();

        // Записываем транзакцию
        BonusTransaction::create([
            'id_user' => $user->user_id,
            'id_order' => null,
            'amount' => $amount,
            'type' => $amount > 0 ? 'EARNED' : 'SPENT',
            'created_at' => now(),
        ]);

        $action = $amount > 0 ? 'начислено' : 'списано';
        return redirect()->back()->with('success', "Пользователю {$action} " . abs($amount) . " бонусов. Причина: {$request->reason}");
    }
}

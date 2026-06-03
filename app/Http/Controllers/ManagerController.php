<?php
// app/Http/Controllers/ManagerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PickupPoint;
use App\Models\BonusTransaction;
use App\Services\LevelService;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    private function checkManagerAccess()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role_id, [2, 3])) {
            abort(403, 'Доступ запрещён. Только для менеджеров.');
        }
    }

    public function orders(Request $request)
    {
        $this->checkManagerAccess();

        $query = Order::with(['user', 'items.product', 'pickupPoint']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pickup_point_id')) {
            $query->where('pickup_point_id', $request->pickup_point_id);
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate(20);

        $pickupPoints = PickupPoint::all();
        $statuses = ['NEW', 'COOKING', 'DELIVERED', 'CANCELLED'];

        $stats = [
            'total' => Order::count(),
            'new' => Order::where('status', 'NEW')->count(),
            'cooking' => Order::where('status', 'COOKING')->count(),
            'delivered' => Order::where('status', 'DELIVERED')->count(),
            'today_orders' => Order::whereDate('order_date', today())->count(),
        ];

        return view('manager.orders', compact('orders', 'pickupPoints', 'statuses', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $this->checkManagerAccess();

        $request->validate([
            'status' => 'required|in:NEW,COOKING,DELIVERED,CANCELLED'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $user = $order->user;
        $levelService = new LevelService();

        // Если статус меняется на DELIVERED (выдан) - начисляем бонусы
        if ($request->status == 'DELIVERED' && $oldStatus != 'DELIVERED') {
            $earnedBonus = $order->earned_bonus_points;

            if ($earnedBonus > 0) {
                // Проверяем, не начисляли ли уже бонусы (чтобы не задвоить)
                $alreadyEarned = BonusTransaction::where('id_order', $order->order_id)
                    ->where('type', 'EARNED')
                    ->exists();

                if (!$alreadyEarned) {
                    BonusTransaction::create([
                        'id_user' => $order->user_id,
                        'id_order' => $order->order_id,
                        'amount' => $earnedBonus,
                        'type' => 'EARNED',
                        'created_at' => now(),
                    ]);

                    $user->bonus_balance += $earnedBonus;
                    $user->save();
                }
            }
        }

        // Если статус меняется на CANCELLED (отменён) - возвращаем списанные бонусы
        if ($request->status == 'CANCELLED' && $oldStatus != 'CANCELLED') {
            $usedBonus = $order->used_bonus_points;

            if ($usedBonus > 0) {
                // Проверяем, не возвращали ли уже бонусы
                $alreadyRefunded = BonusTransaction::where('id_order', $order->order_id)
                    ->where('type', 'REFUNDED')
                    ->exists();

                if (!$alreadyRefunded) {
                    BonusTransaction::create([
                        'id_user' => $order->user_id,
                        'id_order' => $order->order_id,
                        'amount' => $usedBonus,
                        'type' => 'REFUNDED',
                        'created_at' => now(),
                    ]);

                    $user->bonus_balance += $usedBonus;
                    $user->save();
                }
            }
        }

        // Если заказ был DELIVERED, а стал CANCELLED - нужно откатить начисленные бонусы
        if ($oldStatus == 'DELIVERED' && $request->status == 'CANCELLED') {
            $earnedBonus = $order->earned_bonus_points;

            if ($earnedBonus > 0) {
                BonusTransaction::create([
                    'id_user' => $order->user_id,
                    'id_order' => $order->order_id,
                    'amount' => -$earnedBonus,
                    'type' => 'EARNED',
                    'created_at' => now(),
                ]);

                $user->bonus_balance -= $earnedBonus;
                $user->save();
            }
        }

        $order->status = $request->status;
        $order->save();

        // ОБНОВЛЯЕМ УРОВЕНЬ ПОЛЬЗОВАТЕЛЯ
        $newLevel = $levelService->calculateLevel($user);
        if ($user->current_level != $newLevel) {
            $user->current_level = $newLevel;
            $user->save();
        }

        // Обновляем last_3_months_orders_count (количество заказов за последние 3 месяца)
        $ordersCount = Order::where('user_id', $user->user_id)
            ->where('status', 'DELIVERED')
            ->where('order_date', '>=', now()->subMonths(3))
            ->count();
        $user->last_3_months_orders_count = $ordersCount;
        $user->save();

        $statusNames = [
            'NEW' => 'Новый',
            'COOKING' => 'Готовится',
            'DELIVERED' => 'Выдан',
            'CANCELLED' => 'Отменён'
        ];

        return redirect()->back()->with('success', "✅ Заказ #{$order->order_id}: статус изменён с '{$statusNames[$oldStatus]}' на '{$statusNames[$request->status]}'");
    }

    // Просмотр конкретного заказа (опционально)
    public function show($id)
    {
        $this->checkManagerAccess();
        $order = Order::with(['user', 'items.product', 'pickupPoint'])->findOrFail($id);
        return view('manager.order-show', compact('order'));
    }

    // Статистика по бонусам (опционально)
    public function bonusStats()
    {
        $this->checkManagerAccess();

        $totalBonusEarned = BonusTransaction::where('type', 'EARNED')->sum('amount');
        $totalBonusSpent = BonusTransaction::where('type', 'SPENT')->sum('amount');
        $totalBonusRefunded = BonusTransaction::where('type', 'REFUNDED')->sum('amount');

        $stats = [
            'earned' => $totalBonusEarned,
            'spent' => abs($totalBonusSpent),
            'refunded' => $totalBonusRefunded,
            'active_balance' => $totalBonusEarned - abs($totalBonusSpent) + $totalBonusRefunded,
        ];

        return view('manager.bonus-stats', compact('stats'));
    }
}

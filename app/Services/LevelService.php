<?php
// app/Services/LevelService.php

namespace App\Services;

use App\Models\User;
use App\Models\Order;

class LevelService
{
    // Правила для уровней
    private $levels = [
        'BRONZE' => [
            'min_spent' => 0,
            'cashback' => 3,
            'min_orders_3months' => 0,
        ],
        'SILVER' => [
            'min_spent' => 5000,
            'cashback' => 4,
            'min_orders_3months' => 6,
        ],
        'GOLD' => [
            'min_spent' => 15000,
            'cashback' => 5,
            'min_orders_3months' => 9,
        ],
        'PLATINUM' => [
            'min_spent' => 25000,
            'cashback' => 7,
            'min_orders_3months' => 12,
        ],
        'DIAMOND' => [
            'min_spent' => 40000,
            'cashback' => 10,
            'min_orders_3months' => 24,
        ],
    ];

    // Получить уровень пользователя на основе суммы покупок и количества заказов за 3 месяца
    public function calculateLevel(User $user)
    {
        $totalSpent = $user->total_spent;
        $ordersCount3Months = $user->last_3_months_orders_count;

        // Определяем максимальный возможный уровень по сумме
        $levelBySpent = 'BRONZE';
        foreach ($this->levels as $level => $rules) {
            if ($totalSpent >= $rules['min_spent']) {
                $levelBySpent = $level;
            }
        }

        // Проверяем условие сохранения уровня по количеству заказов
        $currentLevelRules = $this->levels[$levelBySpent];
        if ($ordersCount3Months >= $currentLevelRules['min_orders_3months']) {
            return $levelBySpent;
        }

        // Если не прошёл по заказам - понижаем уровень
        return $this->getLowerLevel($levelBySpent, $ordersCount3Months);
    }

    // Понижение уровня, если не выполнено условие по заказам
    private function getLowerLevel($currentLevel, $ordersCount)
    {
        $levelsOrder = ['DIAMOND', 'PLATINUM', 'GOLD', 'SILVER', 'BRONZE'];
        $currentIndex = array_search($currentLevel, $levelsOrder);

        for ($i = $currentIndex; $i >= 0; $i--) {
            $level = $levelsOrder[$i];
            if ($ordersCount >= $this->levels[$level]['min_orders_3months']) {
                return $level;
            }
        }

        return 'BRONZE';
    }

    // Получить кэшбэк для уровня
    public function getCashback($level)
    {
        return $this->levels[$level]['cashback'] ?? 3;
    }

    // Получить все правила для отображения в профиле
    public function getLevelsRules()
    {
        return $this->levels;
    }

    // Рассчитать, сколько нужно потратить до следующего уровня
    public function getNextLevelInfo(User $user)
    {
        $currentLevel = $user->current_level;
        $totalSpent = $user->total_spent;

        $levelsOrder = ['BRONZE', 'SILVER', 'GOLD', 'PLATINUM', 'DIAMOND'];
        $currentIndex = array_search($currentLevel, $levelsOrder);

        // Если уже максимальный уровень
        if ($currentIndex == count($levelsOrder) - 1) {
            return null;
        }

        $nextLevel = $levelsOrder[$currentIndex + 1];
        $nextMinSpent = $this->levels[$nextLevel]['min_spent'];
        $needToSpend = $nextMinSpent - $totalSpent;

        return [
            'level' => $nextLevel,
            'need_to_spend' => $needToSpend > 0 ? $needToSpend : 0,
            'min_spent' => $nextMinSpent,
        ];
    }

    // Обновить количество заказов пользователя за последние 3 месяца
    public function updateOrdersCount(User $user)
    {
        $ordersCount = Order::where('user_id', $user->user_id)
            ->where('status', 'DELIVERED')
            ->where('order_date', '>=', now()->subMonths(3))
            ->count();

        $user->last_3_months_orders_count = $ordersCount;
        $user->save();

        return $ordersCount;
    }
}

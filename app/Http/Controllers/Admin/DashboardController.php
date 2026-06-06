<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\BonusTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function checkAdmin()
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 3) {
            abort(403, 'Доступ запрещён. Только для администраторов.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();

        // Период (по умолчанию текущий месяц)
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        // Основные метрики
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('final_sum');
        $totalUsers = User::count();
        $totalBonusEarned = BonusTransaction::where('type', 'EARNED')->sum('amount');
        $totalBonusSpent = abs(BonusTransaction::where('type', 'SPENT')->sum('amount'));

        // Заказы за период
        $ordersCount = Order::where('order_date', '>=', $startDate)->count();
        $revenuePeriod = Order::where('order_date', '>=', $startDate)->sum('final_sum');

        // Данные для графика (за последние 7 дней)
        $chartData = $this->getChartData();

        // Популярные товары
        $popularProducts = Product::select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.product_id', '=', 'order_items.id_product')
            ->join('orders', 'order_items.id_order', '=', 'orders.order_id')
            ->where('orders.status', 'DELIVERED')
            ->groupBy('products.product_id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Статусы заказов
        $orderStatuses = [
            'NEW' => Order::where('status', 'NEW')->count(),
            'COOKING' => Order::where('status', 'COOKING')->count(),
            'DELIVERED' => Order::where('status', 'DELIVERED')->count(),
            'CANCELLED' => Order::where('status', 'CANCELLED')->count(),
        ];

        // Бонусная статистика
        $bonusStats = [
            'earned' => $totalBonusEarned,
            'spent' => $totalBonusSpent,
            'active_balance' => User::sum('bonus_balance'),
        ];

        // Заказы по дням (для таблицы)
        $ordersByDay = Order::select(
            DB::raw('DATE(order_date) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(final_sum) as revenue')
        )
            ->where('order_date', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalUsers',
            'ordersCount', 'revenuePeriod', 'chartData',
            'popularProducts', 'orderStatuses', 'bonusStats',
            'ordersByDay', 'period'
        ));
    }

    private function getStartDate($period)
    {
        switch ($period) {
            case 'today':
                return now()->startOfDay();
            case 'week':
                return now()->subDays(7);
            case 'month':
                return now()->startOfMonth();
            case 'year':
                return now()->startOfYear();
            default:
                return now()->startOfMonth();
        }
    }

    private function getChartData()
    {
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last7Days->put($date, ['orders' => 0, 'revenue' => 0]);
        }

        $orders = Order::select(
            DB::raw('DATE(order_date) as date'),
            DB::raw('COUNT(*) as orders'),
            DB::raw('SUM(final_sum) as revenue')
        )
            ->where('order_date', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->get();

        foreach ($orders as $order) {
            if ($last7Days->has($order->date)) {
                $last7Days[$order->date] = [
                    'orders' => $order->orders,
                    'revenue' => $order->revenue,
                ];
            }
        }

        return [
            'dates' => $last7Days->keys(),
            'orders' => $last7Days->pluck('orders'),
            'revenue' => $last7Days->pluck('revenue'),
        ];
    }
}

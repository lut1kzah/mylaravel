<?php
// app/Http/Controllers/Admin/PromocodeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promocode;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PromocodeController extends Controller
{
    // Простая проверка прав в каждом методе
    private function checkAdmin()
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 3) {
            abort(403, 'Доступ запрещён. Только для администраторов.');
        }
    }

    // Список промокодов
    public function index()
    {
        $this->checkAdmin();

        $promocodes = Promocode::orderBy('promocode_id', 'desc')->paginate(15);

        foreach ($promocodes as $promocode) {
            $promocode->usage_count = Order::where('used_promocode_id', $promocode->promocode_id)->count();
            $promocode->total_discount = Order::where('used_promocode_id', $promocode->promocode_id)->sum('discount_sum');
        }

        return view('admin.promocodes.index', compact('promocodes'));
    }

    // Форма создания
    public function create()
    {
        $this->checkAdmin();
        return view('admin.promocodes.create');
    }

    // Сохранение нового промокода
    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'code' => 'required|string|max:50|unique:Promocodes,code',
            'discount_percent' => 'required|integer|min:1|max:100',
            'min_order_sum' => 'nullable|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        Promocode::create([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent,
            'min_order_sum' => $request->min_order_sum ?? 0,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.promocodes.index')->with('success', 'Промокод создан!');
    }

    // Форма редактирования
    public function edit($id)
    {
        $this->checkAdmin();
        $promocode = Promocode::findOrFail($id);
        return view('admin.promocodes.edit', compact('promocode'));
    }

    // Обновление промокода
    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $promocode = Promocode::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:Promocodes,code,' . $id . ',promocode_id',
            'discount_percent' => 'required|integer|min:1|max:100',
            'min_order_sum' => 'nullable|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        $promocode->update([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent,
            'min_order_sum' => $request->min_order_sum ?? 0,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.promocodes.index')->with('success', 'Промокод обновлён!');
    }

    // Переключение активности (вкл/выкл)
    public function toggle($id)
    {
        $this->checkAdmin();

        $promocode = Promocode::findOrFail($id);
        $promocode->is_active = !$promocode->is_active;
        $promocode->save();

        $status = $promocode->is_active ? 'активирован' : 'деактивирован';
        return redirect()->back()->with('success', "Промокод {$status}");
    }

    // Просмотр статистики промокода
    public function stats($id)
    {
        $this->checkAdmin();

        $promocode = Promocode::findOrFail($id);
        $orders = Order::where('used_promocode_id', $promocode->promocode_id)
            ->with('user')
            ->orderBy('order_date', 'desc')
            ->paginate(20);

        $totalUsage = Order::where('used_promocode_id', $promocode->promocode_id)->count();
        $totalDiscount = Order::where('used_promocode_id', $promocode->promocode_id)->sum('discount_sum');

        return view('admin.promocodes.stats', compact('promocode', 'orders', 'totalUsage', 'totalDiscount'));
    }
}

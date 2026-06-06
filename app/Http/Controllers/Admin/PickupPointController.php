<?php
// app/Http/Controllers/Admin/PickupPointController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PickupPoint;
use Illuminate\Support\Facades\Auth;

class PickupPointController extends Controller
{
    private function checkAdmin()
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 3) {
            abort(403, 'Доступ запрещён. Только для администраторов.');
        }
    }

    // Список всех ПВЗ
    public function index()
    {
        $this->checkAdmin();
        $pickupPoints = PickupPoint::orderBy('point_id', 'asc')->paginate(15);
        return view('admin.pickup-points.index', compact('pickupPoints'));
    }

    // Форма создания
    public function create()
    {
        $this->checkAdmin();
        return view('admin.pickup-points.create');
    }

    // Сохранение нового ПВЗ
    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'working_hours' => 'required|string|max:100',
        ]);

        PickupPoint::create([
            'address' => $request->address,
            'phone' => $request->phone,
            'working_hours' => $request->working_hours,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.pickup-points.index')->with('success', 'Точка самовывоза добавлена!');
    }

    // Форма редактирования
    public function edit($id)
    {
        $this->checkAdmin();
        $pickupPoint = PickupPoint::findOrFail($id);
        return view('admin.pickup-points.edit', compact('pickupPoint'));
    }

    // Обновление ПВЗ
    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $pickupPoint = PickupPoint::findOrFail($id);

        $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'working_hours' => 'required|string|max:100',
        ]);

        $pickupPoint->update([
            'address' => $request->address,
            'phone' => $request->phone,
            'working_hours' => $request->working_hours,
        ]);

        return redirect()->route('admin.pickup-points.index')->with('success', 'Точка самовывоза обновлена!');
    }

    // Удаление ПВЗ
    public function destroy($id)
    {
        $this->checkAdmin();
        $pickupPoint = PickupPoint::findOrFail($id);
        $pickupPoint->delete();

        return redirect()->route('admin.pickup-points.index')->with('success', 'Точка самовывоза удалена!');
    }
}

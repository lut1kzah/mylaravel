<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
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
}

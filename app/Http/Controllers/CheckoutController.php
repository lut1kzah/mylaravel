<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promocode;

class CheckoutController extends Controller
{
    public function index()
    {

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }
        session(['checkout_subtotal' => $subtotal]);

        $user = Auth::user();
        $bonusBalance = $user->bonus_balance;
        $maxBonusPercent = 25;
        $maxBonusPoints = min($bonusBalance, $subtotal * $maxBonusPercent / 100);

        $discountAmount = session('discount_amount', 0);
        $bonusUsed = session('bonus_used', 0);
        $finalSum = $subtotal - $discountAmount - $bonusUsed;

        return view('checkout', compact('cartItems', 'subtotal', 'bonusBalance', 'maxBonusPoints', 'maxBonusPercent', 'finalSum'));
    }

    public function applyPromocode(Request $request)
    {
        $request->validate(['promocode' => 'required|string']);

        // ✅ Берём полную сумму из сессии
        $subtotal = (float) session('checkout_subtotal', 0);

        $promocode = Promocode::where('code', $request->promocode)
            ->where('is_active', true)
            ->where(function($q) {
                $q->where('valid_from', '<=', now())->orWhereNull('valid_from');
            })
            ->where(function($q) {
                $q->where('valid_until', '>=', now())->orWhereNull('valid_until');
            })
            ->first();

        if ($promocode && $subtotal >= (float)$promocode->min_order_sum) {
            $discountAmount = round($subtotal * $promocode->discount_percent / 100);

            session([
                'discount_amount' => $discountAmount,
                'applied_promocode' => $request->promocode,
                'bonus_used' => 0,
            ]);

            return redirect()->route('checkout')->with('success', "Промокод применён! Скидка {$discountAmount} ₽");
        }

        return redirect()->route('checkout')->with('error', 'Промокод недействителен');
    }

    public function applyBonus(Request $request)
    {
        $request->validate([
            'bonus_points' => 'required|integer|min:0'
        ]);

        $subtotal = session('checkout_subtotal', 0);
        $user = Auth::user();
        $maxBonusPercent = 25;
        $maxBonusPoints = min($user->bonus_balance, $subtotal * $maxBonusPercent / 100);

        $bonusUsed = min($request->bonus_points, $maxBonusPoints);

        session([
            'bonus_used' => $bonusUsed,
            'discount_amount' => 0,
            'applied_promocode' => null,
        ]);

        return redirect()->route('checkout')->with('success', 'Бонусы применены! Списано ' . number_format($bonusUsed, 0, ',', ' ') . ' баллов');
    }

    public function store(Request $request)
    {
        $request->validate(['payment_method' => 'required|in:cash,card']);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->user_id)->with('product')->get();
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }

        $discountAmount = session('discount_amount', 0);
        $bonusUsed = session('bonus_used', 0);
        $finalSum = $subtotal - $discountAmount - $bonusUsed;

        // Находим промокод
        $promocode = null;
        if (session('applied_promocode')) {
            $promocode = Promocode::where('code', session('applied_promocode'))->first();
        }

        $order = Order::create([
            'user_id' => $user->user_id,
            'total_original_sum' => $subtotal,
            'discount_sum' => $discountAmount,
            'final_sum' => $finalSum,
            'used_promocode_id' => $promocode?->promocode_id,
            'used_bonus_points' => $bonusUsed,
            'earned_bonus_points' => floor($finalSum * 0.05),
            'status' => 'NEW',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'id_order' => $order->order_id,
                'id_product' => $item->product_id,
                'product_name' => $item->product->name,
                'price_per_item' => $item->product->price,
                'quantity' => $item->quantity,
            ]);
        }

        // Обновляем бонусы пользователя
        $user->bonus_balance = $user->bonus_balance - $bonusUsed + floor($finalSum * 0.05);
        $user->total_spent += $finalSum;
        $user->save();

        // Очищаем сессию и корзину
        session()->forget(['discount_amount', 'bonus_used', 'applied_promocode']);
        Cart::where('user_id', $user->user_id)->delete();

        return redirect()->route('checkout.success', $order->order_id);
    }

    public function success($id)
    {
        $order = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($id);
        return view('checkout-success', compact('order'));
    }
}

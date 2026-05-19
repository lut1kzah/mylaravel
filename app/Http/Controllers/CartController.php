<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    // Показать корзину
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        return view('cart', compact('cartItems', 'total'));
    }

    // Добавить товар
    public function add($productId)
    {
        $userId = Auth::id();

        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            $cart->quantity += 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Товар добавлен в корзину');
    }

    // Обновить количество
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cart->quantity = $request->quantity;
        $cart->save();

        return redirect()->route('cart')->with('success', 'Количество обновлено');
    }

    // Удалить товар
    public function remove($id)
    {
        Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return redirect()->route('cart')->with('success', 'Товар удалён');
    }

    // Очистить корзину
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('cart')->with('success', 'Корзина очищена');
    }
}

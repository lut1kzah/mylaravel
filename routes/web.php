<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return view('welcome');
    }
    return redirect()->route('login');
})->name('home');

use App\Http\Controllers\Auth\RegisterController;

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', function() {
    return view('login');
})->name('login');

use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\MenuController;
Route::get('/menu', [MenuController::class, 'index'])->name('menu');

use App\Http\Controllers\ProfileController;

Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');

use App\Http\Controllers\CartController;

Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware('auth');
Route::get('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update')->middleware('auth');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('auth');

use App\Http\Controllers\CheckoutController;
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout')->middleware('auth');
Route::post('/checkout/apply-promocode', [CheckoutController::class, 'applyPromocode'])->name('checkout.applyPromocode')->middleware('auth');
Route::post('/checkout/apply-bonus', [CheckoutController::class, 'applyBonus'])->name('checkout.applyBonus')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth');
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success')->middleware('auth');

Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders')->middleware('auth');

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

use App\Http\Controllers\ManagerController;
// Менеджер-панель
Route::prefix('manager')->middleware('auth')->group(function () {
    Route::get('/orders', [ManagerController::class, 'orders'])->name('manager.orders');
    Route::patch('/orders/{id}/status', [ManagerController::class, 'updateStatus'])->name('manager.updateStatus');
    Route::get('/orders/{id}', [ManagerController::class, 'show'])->name('manager.order.show');
});

// Активные заказы пользователя
Route::get('/active-orders', [ProfileController::class, 'activeOrders'])->name('active.orders')->middleware('auth');

use App\Http\Controllers\Admin\PromocodeController;

Route::prefix('admin')->middleware('auth')->group(function () {
    // Статистика (главная админки)
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/promocodes', [PromocodeController::class, 'index'])->name('admin.promocodes.index');
    Route::get('/promocodes/create', [PromocodeController::class, 'create'])->name('admin.promocodes.create');
    Route::post('/promocodes', [PromocodeController::class, 'store'])->name('admin.promocodes.store');
    Route::get('/promocodes/{id}/edit', [PromocodeController::class, 'edit'])->name('admin.promocodes.edit');
    Route::put('/promocodes/{id}', [PromocodeController::class, 'update'])->name('admin.promocodes.update');
    Route::patch('/promocodes/{id}/toggle', [PromocodeController::class, 'toggle'])->name('admin.promocodes.toggle');
    Route::get('/promocodes/{id}/stats', [PromocodeController::class, 'stats'])->name('admin.promocodes.stats');
});
// Временно, пока не созданы контроллеры
Route::prefix('admin')->middleware('auth')->group(function () {
    // Товары
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
    Route::patch('/products/{id}/toggle', [\App\Http\Controllers\Admin\ProductController::class, 'toggle'])->name('admin.products.toggle');

    // ПВЗ
    Route::resource('pickup-points', \App\Http\Controllers\Admin\PickupPointController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.pickup-points.index',
            'create' => 'admin.pickup-points.create',
            'store' => 'admin.pickup-points.store',
            'edit' => 'admin.pickup-points.edit',
            'update' => 'admin.pickup-points.update',
            'destroy' => 'admin.pickup-points.destroy',
        ]);

    // Пользователи
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{id}/adjust-bonus', [\App\Http\Controllers\Admin\UserController::class, 'adjustBonus'])->name('admin.users.adjustBonus');
});
use App\Http\Controllers\PageController;

// Страницы
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/reviews', [PageController::class, 'reviews'])->name('reviews');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
// Добавь после остальных маршрутов PageController
Route::post('/reviews/store', [PageController::class, 'storeReview'])->name('reviews.store');

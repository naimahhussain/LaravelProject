<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

// ── Public routes ──────────────────────────────────────────────
Route::get('/', [FrontController::class, 'index'])->name('home');

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Cart
Route::get('/cart',                    [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product}',     [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{productId}',[CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/checkout',               [CartController::class, 'checkout'])->name('checkout');
Route::get('/cart/count',              [CartController::class, 'count'])->name('cart.count');

// ── Admin routes (requires admin middleware) ────────────────────
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::get('/',         [AdminController::class, 'index'])->name('index');
    Route::get('/stats',    [OrderController::class, 'stats'])->name('stats');

    // Products
    Route::resource('products', ProductController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Orders
    Route::get('orders',                     [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}',             [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])->name('orders.status');
});

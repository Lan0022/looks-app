<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// Route::get('/', function () {
//     return view('welcome');
// });
//Home route
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'showProducts'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'showDetailProduct'])->name('product.show.detail');

// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registration routes
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot password routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');

    // Product routes
    // Route::get('/products', [ProductController::class, 'showProducts'])->name('products');
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'showUserProfile'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateUserProfile'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Cart routes
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'destroy'])->name('cart.remove');

    // Order routes
    Route::get('/checkout', [OrderController::class, 'showOrder'])->name('order');
    Route::post('/checkout', [OrderController::class, 'storeOrder'])->name('order.store');

    // Order history route
    Route::get('/orders', [OrderController::class, 'showOrderHistory'])->name('order.history');
    Route::get('/orders/{order}', [OrderController::class, 'showDetailOrderHistory'])->name('detail-order.history');

    // // Order success route
    // Route::get('/order/success/{orderNumber}', function ($orderNumber) {
    //     $order = Order::where('order_number', $orderNumber)->where('user_id', Auth::id())->firstOrFail();
    //     // Anda bisa membuat view khusus untuk halaman sukses ini
    //     return "<h1>Pesanan Berhasil!</h1><p>Nomor pesanan Anda adalah: {$order->order_number}</p>";
    // })->name('order.success');


    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Product routes
    // Route::get('/products', [ProductController::class, 'showProducts'])->name('products');

    // Dashboard route (example)
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
});

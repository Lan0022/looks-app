<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Models\Product;

// Route::get('/', function () {
//     return view('welcome');
// });
//Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

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

    Route::get('/products', [ProductController::class, 'showProducts'])->name('products');
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'showUserProfile'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateUserProfile'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard route (example)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

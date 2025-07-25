<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Authentication routes group
Route::group(['prefix' => 'auth'], function () {
    // Route to register a new user
    Route::post('register', [AuthController::class, 'register']);

    // Route to login a user
    Route::post('login', [AuthController::class, 'login']);

    // Route to logout the authenticated user
    // Requires the user to be authenticated using Sanctum
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Route to get the authenticated user's information
    // Requires the user to be authenticated using Sanctum
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

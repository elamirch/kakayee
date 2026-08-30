<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('auth/send-otp', [AuthController::class, 'sendotp']);
Route::post('auth/authenticate', [AuthController::class, 'authenticate']);
Route::post('auth/refresh', [AuthController::class, 'refreshTokens']);


Route::middleware(['auth:api', 'check_last_logout'])->group(function () {

    // --- Auth ---
    Route::get('auth/profile', [AuthController::class, 'profile']);
    Route::post('auth/token-info', [AuthController::class, 'tokenInfo']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/logout-all', [AuthController::class, 'logoutAllDevices']);

    // --- Users ---
    Route::apiResource('users', UserController::class);
});
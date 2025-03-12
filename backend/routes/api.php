<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\AuthController;



Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');

    Route::post('/request-password-reset', [AuthController::class, 'requestPasswordReset']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
});




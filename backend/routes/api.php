<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Features\CategoryController;
use App\Http\Controllers\Features\LogController;
use App\Http\Controllers\Features\ScoreController;
use App\Http\Controllers\Features\TransactionController;
use App\Http\Controllers\Users\ProfileController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [UserController::class, 'show']);
    });
});

Route::prefix('password')->group(function () {
    Route::post('forgot', [PasswordController::class, 'forgot']);
    Route::post('reset', [PasswordController::class, 'reset']);
    Route::post('change', [PasswordController::class, 'change'])->middleware('auth:sanctum');
});

Route::prefix('profile')->group(function () {
    Route::post('update', [ProfileController::class, 'update'])->middleware('auth:sanctum');
});

Route::get('user', [UserController::class, 'show'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('logs', LogController::class);
    Route::post('scores', [ScoreController::class, 'check']);
});

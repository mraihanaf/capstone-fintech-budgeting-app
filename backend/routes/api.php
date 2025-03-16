<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

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
    Route::post('/update-profil', [ProfileController::class, 'update'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('budgets', BudgetController::class);
    Route::apiResource('targets', TargetController::class);
    Route::apiResource('reports', ReportController::class);
    Route::apiResource('recommendations', RecommendationController::class);
    Route::apiResource('logs', LogController::class);
});

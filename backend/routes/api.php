<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;



Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');

    Route::post('/request-password-reset', [AuthController::class, 'requestPasswordReset']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('budgets', BudgetController::class);
Route::apiResource('targets', TargetController::class);
Route::apiResource('reports', ReportController::class);
Route::apiResource('recommendations', RecommendationController::class);
Route::apiResource('logs', LogController::class);

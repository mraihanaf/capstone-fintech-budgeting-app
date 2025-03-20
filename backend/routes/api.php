<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Features\BudgetController;
use App\Http\Controllers\Features\CategoryController;
use App\Http\Controllers\Features\LogController;
use App\Http\Controllers\Features\RecommendationController;
use App\Http\Controllers\Features\ReportController;
use App\Http\Controllers\Features\TargetController;
use App\Http\Controllers\Features\TransactionController;
use App\Http\Controllers\Users\ProfileController;
use App\Http\Controllers\Users\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\AdminController;
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

Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::get('admin/users', [AdminController::class, 'getUsers']);
    Route::get('admin/users/{id}', [AdminController::class, 'getUserById']);
    Route::patch('admin/users/{id}/deactivate', [AdminController::class, 'deactivateUser']);

    Route::get('admin/transactions', [AdminController::class, 'getAllTransactions']);
    Route::delete('admin/transactions/{id}', [AdminController::class, 'deleteTransaction']);

    Route::get('admin/categories', [AdminController::class, 'getAllCategories']);
    Route::delete('admin/categories/{id}', [AdminController::class, 'deleteCategory']);

    Route::get('admin/logs', [AdminController::class, 'getLogs']);
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

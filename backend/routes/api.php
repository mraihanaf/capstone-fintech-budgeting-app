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

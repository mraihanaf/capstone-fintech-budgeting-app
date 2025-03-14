<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\ReportRequest;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (ReportRequest $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('transactions', TransactionController::class);

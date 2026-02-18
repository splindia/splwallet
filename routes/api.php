<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Merchant\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum', 'role:merchant'])->prefix('merchant')->group(function () {
    Route::get('/wallet/summary', [WalletController::class, 'summary']);
    Route::post('/wallet/payin/credit', [WalletController::class, 'creditPayin']);
    Route::post('/wallet/payout/debit', [WalletController::class, 'debitPayout']);
});

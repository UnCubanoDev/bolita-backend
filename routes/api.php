<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BetController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RechargeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SettingController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Usuario
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::get('/user/wallet', [UserController::class, 'getWallet']);
    Route::get('/user/bets', [UserController::class, 'getBets']);
    Route::get('/user/referrals', [UserController::class, 'getReferrals']);

    // Apuestas
    Route::post('/bets', [BetController::class, 'store']);
    Route::get('/bets', [BetController::class, 'index']);
    Route::get('/bets/{bet}', [BetController::class, 'show']);
    Route::get('/bets/active', [BetController::class, 'getActiveBets']);

    // Juegos
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/active', [GameController::class, 'getActiveGames']);
    Route::get('/games/{game}', [GameController::class, 'show']);
    Route::get('/games/{game}/results', [GameController::class, 'getResults']);

    // Ruta para solicitar recarga
    Route::post('/recharge', [RechargeController::class, 'store']);

    // Ruta para aprobar una recarga
    Route::post('/recharge/{rechargeRequest}/approve', [RechargeController::class, 'approveRecharge']);

    // Ruta para rechazar una recarga
    Route::post('/recharge/{rechargeRequest}/reject', [RechargeController::class, 'rejectRecharge']);

    // Ruta para solicitar extracción de saldo
    Route::post('/withdrawals', [WithdrawalRequestController::class, 'store']);

    // Ruta para obtener todas las configuraciones
    Route::get('/settings', [SettingController::class, 'index']);
});

<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificacaoEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('email/verificacao', [VerificacaoEmailController::class, 'salvaCodigo']);
    Route::post('email/verificar', [VerificacaoEmailController::class, 'validaCodigo']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

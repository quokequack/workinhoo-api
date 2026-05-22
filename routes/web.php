<?php

use App\Http\Controllers\Auth\AlterarSenhaController;
use App\Http\Controllers\Auth\RecuperacaoSenhaController;
use App\Http\Controllers\Auth\VerificacaoEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('recuperar-senha')->group(function () {
    Route::controller(RecuperacaoSenhaController::class)->group(function () {
        Route::post('enviar-codigo', 'enviaCodigo');
        Route::post('validar', 'validaCodigo');
    });

    Route::controller(AlterarSenhaController::class)->group(function () {
        Route::post('alterar', 'alterarSenha');
    });
});

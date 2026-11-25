<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Support\Facades\Route;

/*
 * Autenticação do Breeze (mesma estrutura do SVO): login, logout e "Meu perfil" (trocar a própria senha).
 * Sem cadastro público: as contas são criadas pelo administrador.
 */
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('perfil', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('perfil/senha', [ProfileController::class, 'updatePassword'])
        ->middleware('throttle:6,1')
        ->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

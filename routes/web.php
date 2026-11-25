<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\BancoController;
use App\Http\Controllers\Admin\FornecedorController;
use App\Http\Controllers\Admin\MovimentacaoController;
use App\Http\Controllers\Admin\ProdutoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Select2Controller;
use App\Support\Perfis;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->middleware('auth')->name('home');

// login, logout e perfil: Breeze (routes/auth.php)
require __DIR__.'/auth.php';

// listas com busca dos <x-select2> (padrão do SVO)
Route::post('select2/{model}', [Select2Controller::class, 'index'])->middleware('auth')->name('select2.list');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // consultar é de todos os perfis; cadastrar e alterar, só de quem tem a permissão
    Route::get('produtos', [ProdutoController::class, 'index'])->name('produtos.index');
    Route::middleware('permission:'.Perfis::PRODUTOS)->group(function () {
        Route::resource('produtos', ProdutoController::class)->except(['index', 'show']);
    });

    Route::get('fornecedores', [FornecedorController::class, 'index'])->name('fornecedores.index');
    Route::middleware('permission:'.Perfis::FORNECEDORES)->group(function () {
        Route::resource('fornecedores', FornecedorController::class)->except(['index', 'show'])
            ->parameters(['fornecedores' => 'fornecedor']);
    });

    Route::get('movimentacoes', [MovimentacaoController::class, 'index'])->name('movimentacoes.index');
    Route::middleware('permission:'.Perfis::MOVIMENTACOES)->group(function () {
        Route::get('movimentacoes/nova', [MovimentacaoController::class, 'create'])->name('movimentacoes.create');
        Route::post('movimentacoes', [MovimentacaoController::class, 'store'])->name('movimentacoes.store');
    });

    // o que o banco publicado tem hoje: migrations aplicadas, tamanho das tabelas, índices e restrições
    Route::get('banco', BancoController::class)->name('banco.index');

    Route::get('auditoria', AuditController::class)->middleware('permission:'.Perfis::AUDITORIA)->name('auditoria.index');
});

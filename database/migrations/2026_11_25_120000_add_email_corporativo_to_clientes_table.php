<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase EXPAND (seção 2.3.3): alteração aditiva e não bloqueante.
 * Coluna nova anulável; a coluna antiga continua existindo e sendo usada.
 * Baixo risco (Tabela 2, linha 1): revisão por pares, sem aprovação do DBA.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('email_corporativo', 150)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('email_corporativo');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Estado inicial do laboratório (seção 2.3.4): clientes com e-mail obrigatório.
 * Mudança aditiva e de baixo risco: revisão por pares basta (Tabela 2, linha 1).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('email', 150);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

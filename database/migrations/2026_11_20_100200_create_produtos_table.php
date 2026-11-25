<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            // índice comum (não único): a carga do sistema antigo trouxe códigos repetidos
            $table->string('codigo', 30)->index();
            $table->string('nome', 150);
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('unidade_medida', 10)->default('UN');
            $table->integer('estoque_minimo')->default(0);
            $table->integer('saldo')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};

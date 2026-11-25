<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos');
            $table->foreignId('fornecedor_id')->nullable()->constrained('fornecedores');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('tipo', 10); // entrada | saida
            $table->integer('quantidade');
            $table->string('documento', 40)->nullable();
            $table->string('observacao', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};

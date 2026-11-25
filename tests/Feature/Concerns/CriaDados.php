<?php

namespace Tests\Feature\Concerns;

use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\User;
use App\Support\Perfis;

trait CriaDados
{
    protected function usuario(string $papel = 'administrador'): User
    {
        Perfis::sync();
        $user = User::query()->create([
            'name' => ucfirst($papel).' de Teste',
            'email' => $papel.'@teste.test',
            'password' => 'senha-de-teste',
        ]);
        $user->assignRole($papel);

        return $user;
    }

    protected function produto(array $dados = []): Produto
    {
        $categoria = Categoria::query()->firstOrCreate(['nome' => 'Expediente']);

        return Produto::query()->forceCreate($dados + [
            'codigo' => 'MAT-'.random_int(10000, 99999),
            'nome' => 'Papel A4 75g',
            'categoria_id' => $categoria->id,
            'unidade_medida' => 'RESMA',
            'estoque_minimo' => 10,
            'saldo' => 0,
            'ativo' => true,
        ]);
    }

    protected function fornecedor(array $dados = []): Fornecedor
    {
        return Fornecedor::query()->forceCreate($dados + [
            'razao_social' => 'Distribuidora Pantanal Ltda',
            'cnpj' => '11222333000181',
        ]);
    }
}

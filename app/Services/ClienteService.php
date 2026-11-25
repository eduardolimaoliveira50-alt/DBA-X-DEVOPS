<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Acesso da aplicação à tabela clientes.
 *
 * Versão 1 (estado inicial): lê e grava somente a coluna email.
 */
class ClienteService
{
    public function cadastrar(string $email): int
    {
        return DB::table('clientes')->insertGetId([
            'email' => $this->normalizar($email),
        ]);
    }

    public function emailDe(int $id): ?string
    {
        return DB::table('clientes')->where('id', $id)->value('email');
    }

    /** @return list<string> */
    public function emails(): array
    {
        return DB::table('clientes')->orderBy('id')->pluck('email')->all();
    }

    private function normalizar(string $email): string
    {
        return mb_strtolower(trim($email));
    }
}

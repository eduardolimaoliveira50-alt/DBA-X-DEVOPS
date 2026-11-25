<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Acesso da aplicação à tabela clientes.
 *
 * Versão 2 (fase Migrate, seção 2.3.4): versão intermediária que GRAVA nas duas colunas
 * (dual-write) e continua LENDO a coluna antiga, compatível com quem ainda usa só "email".
 */
class ClienteService
{
    public function cadastrar(string $email): int
    {
        $email = $this->normalizar($email);

        return DB::table('clientes')->insertGetId([
            'email' => $email,
            'email_corporativo' => $email,
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

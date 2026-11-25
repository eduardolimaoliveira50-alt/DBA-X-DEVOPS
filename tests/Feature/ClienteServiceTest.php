<?php

namespace Tests\Feature;

use App\Services\ClienteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Contrato da aplicação com a tabela clientes, rodando contra PostgreSQL real (nunca SQLite em memória).
 */
class ClienteServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_banco_de_testes_e_postgresql(): void
    {
        $this->assertSame('pgsql', config('database.default'), 'Os testes devem rodar no mesmo SGBD de produção (seção 2.2.2).');
    }

    public function test_cadastra_e_le_o_email(): void
    {
        $service = app(ClienteService::class);

        $id = $service->cadastrar('  Ana@Exemplo.com ');

        $this->assertSame('ana@exemplo.com', $service->emailDe($id));
        $this->assertSame(['ana@exemplo.com'], $service->emails());
    }
}

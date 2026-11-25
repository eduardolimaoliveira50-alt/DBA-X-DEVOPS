<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Teste de integração da fase de coexistência, exatamente como na seção 2.3.4 do trabalho.
 * Deve passar ANTES de a migration de contração existir.
 */
class CompatibilidadeEsquemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_compatibilidade_do_esquema(): void
    {
        DB::table('clientes')->insert([
            'email' => 'teste@exemplo.com',
            'email_corporativo' => 'teste@exemplo.com',
        ]);

        $this->assertDatabaseHas('clientes', [
            'email' => 'teste@exemplo.com',
            'email_corporativo' => 'teste@exemplo.com',
        ]);
        $this->assertSame('teste@exemplo.com', DB::table('clientes')->value('email'));
        $this->assertSame('teste@exemplo.com', DB::table('clientes')->value('email_corporativo'));
    }

    public function test_escritor_antigo_continua_funcionando(): void
    {
        // Versão antiga da aplicação, que só conhece "email": a expansão não pode quebrá-la.
        DB::table('clientes')->insert(['email' => 'antigo@exemplo.com']);

        $this->assertSame(['antigo@exemplo.com'], DB::table('clientes')->pluck('email')->all());
    }
}

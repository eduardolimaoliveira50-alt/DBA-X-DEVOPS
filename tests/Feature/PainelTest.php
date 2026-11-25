<?php

namespace Tests\Feature;

use App\Services\ClienteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PainelTest extends TestCase
{
    use RefreshDatabase;

    public function test_painel_mostra_a_fase_e_as_migrations(): void
    {
        app(ClienteService::class)->cadastrar('ana@exemplo.com');

        $this->get('/')
            ->assertOk()
            ->assertSee('Fase atual do esquema')
            ->assertSee('create_clientes_table')
            ->assertSee('ana@exemplo.com');
    }
}

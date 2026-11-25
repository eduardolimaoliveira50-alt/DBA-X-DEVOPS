<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\CriaDados;
use Tests\TestCase;

class TelasTest extends TestCase
{
    use CriaDados, RefreshDatabase;

    public function test_login_abre_sem_autenticacao(): void
    {
        $this->get('/login')->assertOk()->assertSee('Almoxarifado');
    }

    public function test_telas_principais_abrem_para_o_administrador(): void
    {
        $produto = $this->produto();
        $fornecedor = $this->fornecedor();
        $this->actingAs($this->usuario());

        foreach ([
            '/', '/admin/produtos', '/admin/produtos/create', "/admin/produtos/{$produto->id}/edit",
            '/admin/fornecedores', '/admin/fornecedores/create', "/admin/fornecedores/{$fornecedor->id}/edit",
            '/admin/movimentacoes', '/admin/movimentacoes/nova', '/admin/banco', '/admin/auditoria', '/perfil',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_perfil_consulta_ve_mas_nao_altera(): void
    {
        $this->actingAs($this->usuario('consulta'));

        $this->get('/admin/produtos')->assertOk();
        $this->get('/admin/produtos/create')->assertForbidden();
        $this->get('/admin/movimentacoes/nova')->assertForbidden();
        $this->get('/admin/auditoria')->assertForbidden();
    }

    public function test_busca_do_select2_de_produtos(): void
    {
        $this->produto(['codigo' => 'MAT-00777', 'nome' => 'Toner preto']);
        $this->actingAs($this->usuario());

        $this->postJson('/select2/produto', ['search' => 'toner'])
            ->assertOk()
            ->assertJsonPath('data.0.name', 'MAT-00777 · Toner preto');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Movimentacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\CriaDados;
use Tests\TestCase;

class EstoqueTest extends TestCase
{
    use CriaDados, RefreshDatabase;

    public function test_entrada_soma_no_saldo_e_registra_quem_fez(): void
    {
        $produto = $this->produto();
        $fornecedor = $this->fornecedor();
        $user = $this->usuario('almoxarife');

        $this->actingAs($user)->post('/admin/movimentacoes', [
            'tipo' => 'entrada', 'produto_id' => $produto->id, 'quantidade' => 40,
            'fornecedor_id' => $fornecedor->id, 'documento' => 'NF 1234',
        ])->assertRedirect('/admin/movimentacoes');

        $this->assertSame(40, $produto->fresh()->saldo);
        $this->assertDatabaseHas('movimentacoes', ['produto_id' => $produto->id, 'tipo' => 'entrada', 'user_id' => $user->id]);
    }

    public function test_saida_subtrai_do_saldo(): void
    {
        $produto = $this->produto(['saldo' => 30]);

        $this->actingAs($this->usuario('almoxarife'))->post('/admin/movimentacoes', [
            'tipo' => 'saida', 'produto_id' => $produto->id, 'quantidade' => 12,
        ])->assertRedirect('/admin/movimentacoes');

        $this->assertSame(18, $produto->fresh()->saldo);
    }

    public function test_saida_maior_que_o_saldo_e_recusada(): void
    {
        $produto = $this->produto(['saldo' => 5]);

        $this->actingAs($this->usuario('almoxarife'))
            ->from('/admin/movimentacoes/nova')
            ->post('/admin/movimentacoes', ['tipo' => 'saida', 'produto_id' => $produto->id, 'quantidade' => 6])
            ->assertSessionHasErrors('quantidade');

        $this->assertSame(5, $produto->fresh()->saldo);
        $this->assertSame(0, Movimentacao::query()->count());
    }

    public function test_entrada_exige_fornecedor(): void
    {
        $produto = $this->produto();

        $this->actingAs($this->usuario('almoxarife'))
            ->post('/admin/movimentacoes', ['tipo' => 'entrada', 'produto_id' => $produto->id, 'quantidade' => 3])
            ->assertSessionHasErrors('fornecedor_id');
    }

    public function test_produto_inativo_nao_recebe_movimentacao(): void
    {
        $produto = $this->produto(['ativo' => false, 'saldo' => 10]);

        $this->actingAs($this->usuario('almoxarife'))
            ->post('/admin/movimentacoes', ['tipo' => 'saida', 'produto_id' => $produto->id, 'quantidade' => 1])
            ->assertSessionHasErrors('produto_id');
    }

    public function test_verificacao_de_integridade_aponta_saldo_divergente(): void
    {
        $produto = $this->produto();
        $fornecedor = $this->fornecedor();
        $this->actingAs($this->usuario('almoxarife'))->post('/admin/movimentacoes', [
            'tipo' => 'entrada', 'produto_id' => $produto->id, 'quantidade' => 7, 'fornecedor_id' => $fornecedor->id,
        ]);

        $this->artisan('almox:verificar-integridade')->assertSuccessful();

        $produto->forceFill(['saldo' => 99])->save();
        $this->artisan('almox:verificar-integridade')->assertFailed();
    }
}

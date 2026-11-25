<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\CriaDados;
use Tests\TestCase;

class CadastrosTest extends TestCase
{
    use CriaDados, RefreshDatabase;

    public function test_cadastra_produto_com_codigo_em_maiusculas(): void
    {
        $categoria = Categoria::query()->create(['nome' => 'Limpeza']);

        $this->actingAs($this->usuario())->post('/admin/produtos', [
            'codigo' => ' mat-01234 ', 'nome' => 'Detergente neutro 500ml', 'categoria_id' => $categoria->id,
            'unidade_medida' => 'UN', 'estoque_minimo' => 20, 'ativo' => '1',
        ])->assertRedirect('/admin/produtos');

        $this->assertDatabaseHas('produtos', ['codigo' => 'MAT-01234', 'saldo' => 0]);
    }

    public function test_produto_com_saldo_nao_pode_ser_excluido(): void
    {
        $produto = $this->produto(['saldo' => 3]);

        $this->actingAs($this->usuario())->delete("/admin/produtos/{$produto->id}")->assertSessionHas('error');

        $this->assertNotSoftDeleted($produto);
    }

    public function test_cadastra_fornecedor_com_cnpj_mascarado(): void
    {
        $this->actingAs($this->usuario())->post('/admin/fornecedores', [
            'razao_social' => 'Comercial Cuiabá Ltda', 'cnpj' => '11.222.333/0001-81', 'telefone' => '(65) 3333-1234',
        ])->assertRedirect('/admin/fornecedores');

        $this->assertDatabaseHas('fornecedores', ['cnpj' => '11222333000181', 'telefone' => '6533331234']);
    }

    public function test_cnpj_invalido_e_recusado(): void
    {
        $this->actingAs($this->usuario())
            ->post('/admin/fornecedores', ['razao_social' => 'Empresa X', 'cnpj' => '11.222.333/0001-00'])
            ->assertSessionHasErrors('cnpj');

        $this->assertSame(0, Fornecedor::query()->count());
    }

    public function test_alteracao_de_produto_fica_na_auditoria(): void
    {
        $user = $this->usuario();
        $produto = $this->produto();

        $this->actingAs($user)->put("/admin/produtos/{$produto->id}", [
            'codigo' => $produto->codigo, 'nome' => 'Papel A4 90g', 'categoria_id' => $produto->categoria_id,
            'unidade_medida' => 'RESMA', 'estoque_minimo' => 15, 'ativo' => '1',
        ])->assertRedirect('/admin/produtos');

        $this->get('/admin/auditoria')->assertOk()->assertSee('Papel A4 90g');
        $this->assertSame('Papel A4 90g', Produto::query()->find($produto->id)->nome);
    }
}

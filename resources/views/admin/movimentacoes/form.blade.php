@extends('layouts.app')

@section('title', 'Nova movimentação')

@section('content_header')
    <x-page-header title="Nova movimentação" icon="fas fa-exchange-alt"
                   :crumbs="['Estoque', 'Movimentações' => route('admin.movimentacoes.index'), 'Nova']"
                   subtitle="Entrada aumenta o saldo; saída diminui. O registro não pode ser editado depois." />
@endsection

@section('content')
    <x-form-layout>
        <x-form.form :action="route('admin.movimentacoes.store')" method="POST">
            <x-card title="Dados da movimentação" icon="fas fa-pen" accent="primary">
                <x-form.segmented name="tipo" label="Tipo" :selected="$tipo" :options="[
                    'entrada' => ['label' => 'Entrada', 'icon' => 'fas fa-arrow-down'],
                    'saida' => ['label' => 'Saída', 'icon' => 'fas fa-arrow-up'],
                ]" />

                <div class="form-row">
                    <x-select2 name="produto_id" model="produto" label="Produto" col="8" :selected="$produtoId"
                               placeholder="Código ou nome" required help="Só produtos ativos." />
                    <x-form.input name="quantidade" label="Quantidade" col="4" type="number" min="1" icon="fas fa-hashtag" required />
                </div>

                <div data-show-when="tipo=entrada">
                    <div class="form-row">
                        <x-select2 name="fornecedor_id" model="fornecedor" label="Fornecedor" col="8"
                                   placeholder="Razão social ou CNPJ" required />
                        <x-form.input name="documento" label="Nota fiscal" col="4" icon="fas fa-file-invoice" maxlength="40" placeholder="NF 123456" />
                    </div>
                </div>

                <x-form.input name="observacao" label="Observação" icon="fas fa-comment" maxlength="255"
                              placeholder="Ex.: requisição do setor de compras" />

                <x-slot:footer>
                    <div class="d-flex flex-wrap" style="gap: .5rem;">
                        <x-btn.submit label="Registrar movimentação" />
                        <x-btn.back :route="route('admin.movimentacoes.index')" />
                    </div>
                </x-slot:footer>
            </x-card>
        </x-form.form>

        <x-slot:aside>
            <x-card title="Como funciona" icon="fas fa-info-circle">
                <div class="ca-callout mb-3">
                    <i class="fas fa-lock"></i>
                    <div>O saldo e a movimentação são gravados juntos, com o produto travado: duas saídas ao mesmo tempo não deixam o saldo negativo.</div>
                </div>
                <div class="ca-callout ca-callout--accent">
                    <i class="fas fa-lightbulb"></i>
                    <div>Errou? Registre a movimentação contrária com uma observação. O histórico não se apaga.</div>
                </div>
            </x-card>
        </x-slot:aside>
    </x-form-layout>
@endsection

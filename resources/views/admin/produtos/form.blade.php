@extends('layouts.app')

@php($editing = $produto->exists)

@section('title', $editing ? 'Editar produto' : 'Novo produto')

@section('content_header')
    <x-page-header :title="$editing ? 'Editar '.$produto->codigo : 'Novo produto'" icon="fas fa-box-open"
                   :crumbs="['Estoque', 'Produtos' => route('admin.produtos.index'), $editing ? 'Editar' : 'Novo']"
                   subtitle="O saldo não é editado aqui: ele muda pelas movimentações de entrada e saída." />
@endsection

@section('content')
    <x-form-layout>
        <x-form.form :action="$editing ? route('admin.produtos.update', $produto) : route('admin.produtos.store')"
                     :method="$editing ? 'PUT' : 'POST'">
            <x-card title="Dados do produto" icon="fas fa-pen" accent="primary">
                <div class="form-row">
                    <x-form.input name="codigo" label="Código" col="4" icon="fas fa-barcode" :value="$produto->codigo"
                                  placeholder="MAT-00001" maxlength="30" required autofocus
                                  help="Código interno do almoxarifado." />
                    <x-form.input name="nome" label="Nome" col="8" icon="fas fa-box" :value="$produto->nome"
                                  placeholder="Papel A4 75g" maxlength="150" required />
                </div>
                <div class="form-row">
                    <x-form.select name="categoria_id" label="Categoria" col="4" :options="$categorias"
                                   :selected="$produto->categoria_id" placeholder="Escolha" required />
                    <x-form.select name="unidade_medida" label="Unidade de medida" col="4" :options="\App\Models\Produto::UNIDADES"
                                   :selected="$produto->unidade_medida" required />
                    <x-form.input name="estoque_minimo" label="Estoque mínimo" col="4" type="number" min="0" icon="fas fa-level-down-alt"
                                  :value="$produto->estoque_minimo ?? 0" required help="Abaixo disso, o produto aparece em Reposição." />
                </div>
                <div class="custom-control custom-switch">
                    <input type="hidden" name="ativo" value="0">
                    <input type="checkbox" class="custom-control-input" id="f-ativo" name="ativo" value="1" @checked(old('ativo', $produto->ativo))>
                    <label class="custom-control-label" for="f-ativo">Ativo (aparece para movimentação)</label>
                </div>

                <x-slot:footer>
                    <div class="d-flex flex-wrap" style="gap: .5rem;">
                        <x-btn.submit :label="$editing ? 'Salvar alterações' : 'Cadastrar produto'" />
                        <x-btn.back :route="route('admin.produtos.index')" />
                    </div>
                </x-slot:footer>
            </x-card>
        </x-form.form>

        <x-slot:aside>
            <x-card title="Como funciona" icon="fas fa-info-circle">
                @if ($editing)
                    <div class="ca-callout mb-3">
                        <i class="fas fa-cubes"></i>
                        <div>Saldo atual: <strong>{{ number_format($produto->saldo, 0, ',', '.') }} {{ $produto->unidade_medida }}</strong>.</div>
                    </div>
                @endif
                <div class="ca-callout mb-3">
                    <i class="fas fa-exchange-alt"></i>
                    <div>O saldo só muda por <strong>Movimentações</strong>: cada entrada e saída fica registrada com quem fez e quando.</div>
                </div>
                <div class="ca-callout ca-callout--accent">
                    <i class="fas fa-lightbulb"></i>
                    <div>Produto inativo continua no histórico, mas não aparece para novas movimentações.</div>
                </div>
            </x-card>
        </x-slot:aside>
    </x-form-layout>
@endsection

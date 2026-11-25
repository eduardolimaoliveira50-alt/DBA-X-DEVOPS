@extends('layouts.app')

@section('title', 'Produtos')

@php($n = fn ($v) => number_format((int) $v, 0, ',', '.'))

@section('content_header')
    <x-page-header title="Produtos" icon="fas fa-box-open" :crumbs="['Estoque', 'Produtos']"
                   subtitle="O catálogo do almoxarifado: código, saldo atual e estoque mínimo de cada material.">
        <x-slot:aside>
            <x-btn.new :route="route('admin.produtos.create')" title="Novo produto" can="produtos.gerenciar" />
        </x-slot:aside>
    </x-page-header>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.produtos.index') }}" class="mb-3" data-submit-once="false">
        <div class="form-row">
            <div class="col-lg-6 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                    <input type="search" name="busca" value="{{ $busca }}" class="form-control" aria-label="Buscar produto"
                           placeholder="Buscar por código ou nome">
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-2">
                <select name="categoria" class="custom-select" aria-label="Categoria" data-autosubmit onchange="this.form.submit()">
                    <option value="">Todas as categorias</option>
                    @foreach ($categorias as $id => $nome)
                        <option value="{{ $id }}" @selected((int) request('categoria') === $id)>{{ $nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-3 mb-2 d-flex" style="gap: .5rem;">
                <select name="situacao" class="custom-select" aria-label="Situação" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="abaixo" @selected(request('situacao') === 'abaixo')>Abaixo do mínimo</option>
                    <option value="inativos" @selected(request('situacao') === 'inativos')>Inativos</option>
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
        @if ($busca !== '' || request('categoria') || request('situacao'))
            <a href="{{ route('admin.produtos.index') }}" class="btn btn-link btn-sm px-0"><i class="fas fa-times mr-1"></i> Limpar filtros</a>
        @endif
    </form>

    <x-card :title="$busca !== '' ? 'Encontrados' : 'Cadastrados'" icon="fas fa-list-ul" :count="$n($produtos->total())" :no-padding="true">
        @if ($produtos->isEmpty())
            <x-empty-state icon="fas fa-box-open" title="Nenhum produto encontrado">
                Confira o que foi digitado ou tire os filtros.
                <x-slot:actions>
                    <x-btn.new :route="route('admin.produtos.create')" title="Cadastrar produto" can="produtos.gerenciar" />
                </x-slot:actions>
            </x-empty-state>
        @else
            <x-table>
                <x-slot:head>
                    <tr>
                        <th>Código</th>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th class="text-right">Saldo</th>
                        <th class="text-right">Mínimo</th>
                        <th>Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </x-slot:head>

                @foreach ($produtos as $produto)
                    <tr>
                        <td class="text-nowrap"><x-code :value="$produto->codigo" /></td>
                        <td>
                            <span class="ca-person">
                                <span class="ca-person__avatar"><i class="fas fa-box"></i></span>
                                <span class="ca-person__name">{{ $produto->nome }}</span>
                            </span>
                        </td>
                        <td class="small">{{ $produto->categoria?->nome }}</td>
                        <td class="text-right text-nowrap font-weight-bold">{{ $n($produto->saldo) }} <span class="small text-muted">{{ $produto->unidade_medida }}</span></td>
                        <td class="text-right text-muted">{{ $n($produto->estoque_minimo) }}</td>
                        <td>
                            @if (! $produto->ativo)
                                <x-badge color="muted">Inativo</x-badge>
                            @elseif ($produto->abaixoDoMinimo())
                                <x-badge color="danger" icon="fas fa-exclamation-triangle">Repor</x-badge>
                            @else
                                <x-badge color="success">Normal</x-badge>
                            @endif
                        </td>
                        <td class="text-right text-nowrap">
                            <a href="{{ route('admin.movimentacoes.index', ['produto' => $produto->id]) }}" class="btn btn-sm btn-outline-secondary"
                               data-toggle="tooltip" title="Movimentações deste produto"><i class="fas fa-exchange-alt"></i></a>
                            <x-btn.edit :route="route('admin.produtos.edit', $produto)" can="produtos.gerenciar" />
                            @can('produtos.gerenciar')
                                <x-btn.confirm :route="route('admin.produtos.destroy', $produto)" method="DELETE"
                                               label="Excluir" icon="fas fa-trash-alt" variant="soft-danger"
                                               :confirm="'Excluir o produto '.$produto->codigo.'? As movimentações continuam no histórico.'"
                                               confirm-title="Excluir produto" confirm-button="Sim, excluir" confirm-variant="danger" />
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif

        @if ($produtos->hasPages())
            <x-slot:footer>
                {{ $produtos->links() }}
            </x-slot:footer>
        @endif
    </x-card>
@endsection

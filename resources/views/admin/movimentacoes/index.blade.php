@extends('layouts.app')

@section('title', 'Movimentações')

@php($n = fn ($v) => number_format((int) $v, 0, ',', '.'))

@section('content_header')
    <x-page-header title="Movimentações" icon="fas fa-exchange-alt" :crumbs="['Estoque', 'Movimentações']"
                   subtitle="Cada entrada e saída de material, com quem registrou e quando. É daqui que vem o saldo dos produtos.">
        <x-slot:aside>
            @can('movimentacoes.registrar')
                <a href="{{ route('admin.movimentacoes.create', ['tipo' => 'saida']) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-up mr-1"></i> Saída</a>
                <a href="{{ route('admin.movimentacoes.create', ['tipo' => 'entrada']) }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-down mr-1"></i> Entrada</a>
            @endcan
        </x-slot:aside>
    </x-page-header>
@endsection

@section('content')
    <x-filter-card :action="route('admin.movimentacoes.index')" :keys="['tipo', 'produto', 'de', 'ate']">
        <x-form.select name="tipo" label="Tipo" col="2" :options="\App\Models\Movimentacao::TIPOS" :selected="request('tipo')" placeholder="Todos" />
        <x-select2 name="produto" model="produto" label="Produto" col="4" :selected="$produto?->id" placeholder="Código ou nome" />
        <x-form.input name="de" label="De" type="date" col="3" :value="request('de')" />
        <x-form.input name="ate" label="Até" type="date" col="3" :value="request('ate')" />
    </x-filter-card>

    <x-card :title="$produto ? 'Movimentações de '.$produto->nome : 'Mais recentes'" icon="fas fa-list-ul" :no-padding="true"
            :subtitle="$produto ? 'Saldo atual: '.$n($produto->saldo).' '.$produto->unidade_medida : null">
        @if ($movimentacoes->isEmpty())
            <x-empty-state icon="fas fa-exchange-alt" title="Nenhuma movimentação encontrada">Mude o período ou tire os filtros.</x-empty-state>
        @else
            <x-table class="table-sm">
                <x-slot:head>
                    <tr>
                        <th>Quando</th>
                        <th>Tipo</th>
                        <th>Produto</th>
                        <th class="text-right">Quantidade</th>
                        <th>Fornecedor / documento</th>
                        <th>Registrada por</th>
                    </tr>
                </x-slot:head>

                @foreach ($movimentacoes as $mov)
                    <tr>
                        <td class="text-nowrap">
                            <div class="font-weight-bold">{{ $mov->created_at?->format('d/m/Y') }}</div>
                            <div class="small text-muted">{{ $mov->created_at?->format('H:i') }}</div>
                        </td>
                        <td>
                            @if ($mov->isEntrada())
                                <x-badge color="success" icon="fas fa-arrow-down">Entrada</x-badge>
                            @else
                                <x-badge color="danger" icon="fas fa-arrow-up">Saída</x-badge>
                            @endif
                        </td>
                        <td>
                            <div class="font-weight-bold">{{ $mov->produto?->nome }}</div>
                            <div class="small text-muted">{{ $mov->produto?->codigo }}</div>
                        </td>
                        <td class="text-right text-nowrap font-weight-bold">{{ $mov->isEntrada() ? '+' : '−' }}{{ $n($mov->quantidade) }} <span class="small text-muted">{{ $mov->produto?->unidade_medida }}</span></td>
                        <td class="small">
                            <div>{{ $mov->fornecedor?->razao_social ?? '—' }}</div>
                            <div class="text-muted">{{ $mov->documento }}</div>
                        </td>
                        <td class="small">{{ $mov->user?->name ?? 'carga do sistema antigo' }}</td>
                    </tr>
                @endforeach
            </x-table>
        @endif

        @if ($movimentacoes->hasPages())
            <x-slot:footer>
                {{ $movimentacoes->links() }}
            </x-slot:footer>
        @endif
    </x-card>
@endsection

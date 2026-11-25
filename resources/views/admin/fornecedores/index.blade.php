@extends('layouts.app')

@section('title', 'Fornecedores')

@section('content_header')
    <x-page-header title="Fornecedores" icon="fas fa-truck" :crumbs="['Estoque', 'Fornecedores']"
                   subtitle="Quem entrega o material. Toda entrada no estoque indica o fornecedor.">
        <x-slot:aside>
            <x-btn.new :route="route('admin.fornecedores.create')" title="Novo fornecedor" can="fornecedores.gerenciar" />
        </x-slot:aside>
    </x-page-header>
@endsection

@section('content')
    @if ($totalSemCnpj > 0)
        <div class="ca-callout ca-callout--accent mb-3">
            <i class="fas fa-id-card"></i>
            <div>
                <strong>{{ $totalSemCnpj }} fornecedor(es) sem CNPJ</strong>, vindos do cadastro antigo.
                <a href="{{ route('admin.fornecedores.index', ['sem_cnpj' => 1]) }}">Ver a lista</a> para completar.
            </div>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.fornecedores.index') }}" class="mb-3" data-submit-once="false">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
            <input type="search" name="busca" value="{{ $busca }}" class="form-control" aria-label="Buscar fornecedor"
                   placeholder="Buscar por razão social ou CNPJ">
            @if ($semCnpj)
                <input type="hidden" name="sem_cnpj" value="1">
            @endif
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
                @if ($busca !== '' || $semCnpj)
                    <a href="{{ route('admin.fornecedores.index') }}" class="btn btn-outline-secondary">Limpar</a>
                @endif
            </div>
        </div>
    </form>

    <x-card :title="$semCnpj ? 'Sem CNPJ' : 'Cadastrados'" icon="fas fa-list-ul" :count="$fornecedores->total()" :no-padding="true">
        @if ($fornecedores->isEmpty())
            <x-empty-state icon="fas fa-truck" title="Nenhum fornecedor encontrado">Confira o que foi digitado.</x-empty-state>
        @else
            <x-table>
                <x-slot:head>
                    <tr>
                        <th>Fornecedor</th>
                        <th>CNPJ</th>
                        <th>Contato</th>
                        <th class="text-center">Entradas</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </x-slot:head>

                @foreach ($fornecedores as $fornecedor)
                    <tr>
                        <td>
                            <span class="ca-person">
                                <span class="ca-person__avatar"><i class="fas fa-truck"></i></span>
                                <span class="ca-person__name">{{ $fornecedor->razao_social }}</span>
                            </span>
                        </td>
                        <td class="text-nowrap">
                            @if ($fornecedor->cnpj)
                                {{ \App\Support\Cnpj::format($fornecedor->cnpj) }}
                            @else
                                <x-badge color="warning" icon="fas fa-exclamation-circle">pendente</x-badge>
                            @endif
                        </td>
                        <td class="small">
                            <div>{{ $fornecedor->email ?? '—' }}</div>
                            <div class="text-muted">{{ $fornecedor->telefone ?? '' }}</div>
                        </td>
                        <td class="text-center"><x-badge :color="$fornecedor->movimentacoes_count ? 'primary' : 'muted'" :dot="false">{{ number_format($fornecedor->movimentacoes_count, 0, ',', '.') }}</x-badge></td>
                        <td class="text-right text-nowrap">
                            <x-btn.edit :route="route('admin.fornecedores.edit', $fornecedor)" can="fornecedores.gerenciar" />
                            @can('fornecedores.gerenciar')
                                <x-btn.confirm :route="route('admin.fornecedores.destroy', $fornecedor)" method="DELETE"
                                               label="Excluir" icon="fas fa-trash-alt" variant="soft-danger"
                                               :confirm="'Excluir o fornecedor '.$fornecedor->razao_social.'? As entradas antigas continuam mostrando o nome.'"
                                               confirm-title="Excluir fornecedor" confirm-button="Sim, excluir" confirm-variant="danger" />
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif

        @if ($fornecedores->hasPages())
            <x-slot:footer>
                {{ $fornecedores->links() }}
            </x-slot:footer>
        @endif
    </x-card>
@endsection

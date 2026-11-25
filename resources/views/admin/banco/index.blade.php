@extends('layouts.app')

@section('title', 'Banco de dados')

@php
    $n = fn ($v) => number_format((int) $v, 0, ',', '.');
    $tipos = ['c' => ['CHECK', 'info'], 'u' => ['UNIQUE', 'primary'], 'f' => ['FOREIGN KEY', 'muted']];
@endphp

@section('content_header')
    <x-page-header title="Banco de dados" icon="fas fa-database" :crumbs="['Acompanhamento', 'Banco de dados']"
                   subtitle="O que está aplicado neste banco agora: migrations, tamanho das tabelas, índices e restrições. Tudo veio de migrations versionadas no Git." />
@endsection

@section('content')
    <div class="row">
        @foreach ($tabelas as $tabela)
            <div class="col-sm-6 col-xl-3 mb-4">
                <x-kpi :label="$tabela['nome']" :value="$n($tabela['linhas'])" icon="fas fa-table" :color="['primary', 'accent', 'success', 'info'][$loop->index]"
                       :hint="'linhas'.($tabela['tamanho'] ? ' · '.$tabela['tamanho'].' em disco' : '')" />
            </div>
        @endforeach
    </div>

    <div class="row ca-cols-stretch">
        <div class="col-xl-5 mb-4">
            <x-card title="Migrations aplicadas" icon="fas fa-layer-group" :count="$migrations->count()" :no-padding="true" class="h-100"
                    :subtitle="'Da mais nova para a mais antiga · '.$servidor">
                <x-table class="table-sm">
                    <x-slot:head><tr><th>Lote</th><th>Migration</th></tr></x-slot:head>
                    @foreach ($migrations as $migration)
                        <tr>
                            <td class="text-center"><x-badge :color="$loop->first ? 'accent' : 'muted'" :dot="false">{{ $migration->batch }}</x-badge></td>
                            <td class="small"><code>{{ $migration->migration }}</code></td>
                        </tr>
                    @endforeach
                </x-table>
            </x-card>
        </div>
        <div class="col-xl-7 mb-4">
            <x-card title="Índices" icon="fas fa-bolt" :count="count($indices) ?: null" :no-padding="true" class="h-100"
                    subtitle="Como o PostgreSQL encontra as linhas sem ler a tabela inteira">
                @if ($indices === [])
                    <x-empty-state icon="fas fa-bolt" title="Disponível só no PostgreSQL" />
                @else
                    <x-table class="table-sm">
                        <x-slot:head><tr><th>Tabela</th><th>Índice</th><th>Definição</th></tr></x-slot:head>
                        @foreach ($indices as $indice)
                            <tr>
                                <td class="small">{{ $indice->tabela }}</td>
                                <td class="small font-weight-bold">{{ $indice->nome }}</td>
                                <td class="small"><code>{{ \Illuminate\Support\Str::after($indice->definicao, ' USING ') }}</code></td>
                            </tr>
                        @endforeach
                    </x-table>
                @endif
            </x-card>
        </div>
    </div>

    <div class="row ca-cols-stretch">
        <div class="col-xl-7 mb-4">
            <x-card title="Restrições" icon="fas fa-shield-alt" :no-padding="true" class="h-100"
                    subtitle="Regras que o próprio banco garante. NOT VALID: vale para as linhas novas; as antigas ainda não foram conferidas.">
                @if ($restricoes === [])
                    <x-empty-state icon="fas fa-shield-alt" title="Disponível só no PostgreSQL" />
                @else
                    <x-table class="table-sm">
                        <x-slot:head><tr><th>Tabela</th><th>Restrição</th><th>Tipo</th><th>Definição</th></tr></x-slot:head>
                        @foreach ($restricoes as $restricao)
                            @php([$rotulo, $cor] = $tipos[$restricao->tipo] ?? [$restricao->tipo, 'muted'])
                            <tr>
                                <td class="small">{{ $restricao->tabela }}</td>
                                <td class="small font-weight-bold">{{ $restricao->nome }}</td>
                                <td class="text-nowrap">
                                    <x-badge :color="$cor" :dot="false">{{ $rotulo }}</x-badge>
                                    @unless ($restricao->validada)
                                        <x-badge color="warning" icon="fas fa-hourglass-half">NOT VALID</x-badge>
                                    @endunless
                                </td>
                                <td class="small"><code>{{ $restricao->definicao }}</code></td>
                            </tr>
                        @endforeach
                    </x-table>
                @endif
            </x-card>
        </div>
        <div class="col-xl-5 mb-4">
            <x-card title="Colunas" icon="fas fa-columns" :no-padding="true" class="h-100" subtitle="produtos e fornecedores">
                <x-table class="table-sm">
                    <x-slot:head><tr><th>Coluna</th><th>Tipo</th><th class="text-center">Aceita vazio</th></tr></x-slot:head>
                    @foreach ($colunas as $tabela => $lista)
                        <tr><td colspan="3" class="small text-uppercase text-muted font-weight-bold pt-3">{{ $tabela }}</td></tr>
                        @foreach ($lista as $coluna)
                            <tr>
                                <td class="small"><code>{{ $coluna['name'] }}</code></td>
                                <td class="small">{{ $coluna['type'] }}</td>
                                <td class="text-center small">{{ $coluna['nullable'] ? 'sim' : 'não' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </x-table>
            </x-card>
        </div>
    </div>
@endsection

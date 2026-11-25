@extends('layouts.app')

@section('title', 'Início')

@php
    $usuario = auth()->user();
    $primeiroNome = \Illuminate\Support\Str::before(trim($usuario->name), ' ');
    $n = fn ($v) => number_format((int) $v, 0, ',', '.');
@endphp

@section('content')
    <div class="ca-hero mt-3">
        <div class="ca-hero__eyebrow">{{ config('sistema.nome') }}</div>
        <div class="ca-hero__title">Olá, {{ $primeiroNome }}</div>
        <p class="ca-hero__text">
            Entradas e saídas de material, saldo de cada produto e o que precisa de reposição.
            @can('movimentacoes.registrar') Registre uma movimentação e o saldo é atualizado na hora. @endcan
        </p>
        <div class="ca-hero__chips">
            @foreach ($usuario->getRoleNames() as $perfil)
                <span class="ca-hero__chip"><i class="fas fa-id-badge"></i> {{ \App\Support\Perfis::ROLE_LABELS[$perfil] ?? $perfil }}</span>
            @endforeach
            <span class="ca-hero__chip" data-toggle="tooltip" title="Commit publicado nesta instalação">
                <i class="fas fa-code-branch"></i> Versão {{ config('sistema.versao') }}
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xl-3 mb-4">
            <x-kpi label="Produtos ativos" :value="$n($stats['produtos'])" icon="fas fa-box-open" color="primary"
                   hint="no catálogo do almoxarifado" :href="route('admin.produtos.index')" />
        </div>
        <div class="col-sm-6 col-xl-3 mb-4">
            <x-kpi label="Abaixo do mínimo" :value="$n($stats['abaixo'])" icon="fas fa-exclamation-triangle" color="danger"
                   hint="precisam de reposição" :href="route('admin.produtos.index', ['situacao' => 'abaixo'])" />
        </div>
        <div class="col-sm-6 col-xl-3 mb-4">
            <x-kpi label="Entradas (30 dias)" :value="$n($stats['entradas'])" icon="fas fa-arrow-down" color="success"
                   hint="unidades recebidas" :href="route('admin.movimentacoes.index', ['tipo' => 'entrada'])" />
        </div>
        <div class="col-sm-6 col-xl-3 mb-4">
            <x-kpi label="Saídas (30 dias)" :value="$n($stats['saidas'])" icon="fas fa-arrow-up" color="accent"
                   hint="unidades entregues" :href="route('admin.movimentacoes.index', ['tipo' => 'saida'])" />
        </div>
    </div>

    <div class="row ca-cols-stretch">
        <div class="col-lg-7 mb-4">
            <x-card title="Reposição urgente" icon="fas fa-exclamation-triangle" :count="$stats['abaixo'] ?: null" :no-padding="true" class="h-100"
                    subtitle="Os mais distantes do estoque mínimo">
                <x-slot:tools>
                    <a href="{{ route('admin.produtos.index', ['situacao' => 'abaixo']) }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </x-slot:tools>
                @if ($criticos->isEmpty())
                    <x-empty-state icon="fas fa-check-circle" title="Nada abaixo do mínimo">Todos os produtos ativos têm saldo suficiente.</x-empty-state>
                @else
                    <x-table class="table-sm">
                        <x-slot:head><tr><th>Código</th><th>Produto</th><th class="text-right">Saldo</th><th class="text-right">Mínimo</th><th></th></tr></x-slot:head>
                        @foreach ($criticos as $produto)
                            <tr>
                                <td><x-code :value="$produto->codigo" :copy="false" /></td>
                                <td>
                                    <div class="font-weight-bold">{{ $produto->nome }}</div>
                                    <div class="small text-muted">{{ $produto->categoria?->nome }}</div>
                                </td>
                                <td class="text-right"><x-badge color="danger" :dot="false">{{ $n($produto->saldo) }} {{ $produto->unidade_medida }}</x-badge></td>
                                <td class="text-right text-muted">{{ $n($produto->estoque_minimo) }}</td>
                                <td class="text-right">
                                    @can('movimentacoes.registrar')
                                        <a href="{{ route('admin.movimentacoes.create', ['tipo' => 'entrada', 'produto' => $produto->id]) }}"
                                           class="btn btn-sm btn-outline-success" data-toggle="tooltip" title="Registrar entrada"><i class="fas fa-arrow-down"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                @endif
            </x-card>
        </div>
        <div class="col-lg-5 mb-4">
            <x-card title="Últimas movimentações" icon="fas fa-exchange-alt" class="h-100" subtitle="O que entrou e saiu por último">
                <x-slot:tools>
                    <a href="{{ route('admin.movimentacoes.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </x-slot:tools>
                @if ($ultimas->isEmpty())
                    <x-empty-state icon="fas fa-exchange-alt" title="Nenhuma movimentação ainda" />
                @else
                    <ul class="ca-timeline">
                        @foreach ($ultimas as $mov)
                            <li>
                                <div class="ca-timeline__head">
                                    <i class="fas {{ $mov->isEntrada() ? 'fa-arrow-down text-success' : 'fa-arrow-up text-danger' }}"></i>
                                    <strong class="small">{{ $mov->isEntrada() ? 'Entrada' : 'Saída' }} de {{ $n($mov->quantidade) }} {{ $mov->produto?->unidade_medida }}</strong>
                                    <span class="small text-muted ml-auto text-nowrap">{{ $mov->created_at?->diffForHumans(short: true) }}</span>
                                </div>
                                <div class="small text-muted">{{ $mov->produto?->nome }}@if ($mov->user) · {{ $mov->user->name }}@endif</div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>
        </div>
    </div>
@endsection

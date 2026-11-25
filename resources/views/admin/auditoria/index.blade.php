@extends('layouts.app')

@section('title', 'Auditoria')

@php
    $objectLabels = [
        'Produto' => ['Produto', 'fas fa-box'],
        'Fornecedor' => ['Fornecedor', 'fas fa-truck'],
    ];
    $format = function ($value) {
        if (is_null($value) || $value === '') {
            return '—';
        }
        if (is_bool($value)) {
            return $value ? 'sim' : 'não';
        }

        return is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE);
    };
@endphp

@section('content_header')
    <x-page-header title="Auditoria" icon="fas fa-history" :crumbs="['Acompanhamento', 'Auditoria']"
                   subtitle="Quem cadastrou ou alterou produtos e fornecedores: quando e o que mudou." />
@endsection

@section('content')
    <x-filter-card :action="route('admin.auditoria.index')" :keys="['filter']">
        <x-form.select name="filter[event]" label="Evento" col="3"
                       :options="['created' => 'Criação', 'updated' => 'Alteração', 'deleted' => 'Exclusão']"
                       :selected="request('filter.event')" placeholder="Todos" />
        <x-form.select name="filter[causer_id]" label="Quem fez" col="4" :options="$users->pluck('name', 'id')"
                       :selected="request('filter.causer_id')" placeholder="Todos" />
    </x-filter-card>

    <x-card title="Registros" icon="fas fa-clipboard-list" :count="$activities->total()" :no-padding="true">
        @if ($activities->isEmpty())
            <x-empty-state icon="fas fa-history" title="Nada registrado ainda">As alterações em produtos e fornecedores aparecem aqui.</x-empty-state>
        @else
            <x-table class="table-sm">
                <x-slot:head>
                    <tr><th>Quando</th><th>Quem</th><th>Evento</th><th>Objeto</th><th>O que mudou</th></tr>
                </x-slot:head>

                @foreach ($activities as $activity)
                    @php
                        [$objectLabel, $objectIcon] = $objectLabels[class_basename((string) $activity->subject_type)] ?? [class_basename((string) $activity->subject_type), 'fas fa-cube'];
                        $changes = $activity->changes();
                        $new = collect($changes['attributes'] ?? []);
                        $old = $changes['old'] ?? [];
                        if ($activity->event === 'created') {
                            $new = $new->reject(fn ($value) => is_null($value) || $value === '');
                        }
                    @endphp
                    <tr>
                        <td class="text-nowrap">
                            <div class="font-weight-bold">{{ $activity->created_at->format('H:i:s') }}</div>
                            <div class="small text-muted">{{ $activity->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            @if ($activity->causer)
                                <x-person :name="$activity->causer->name" :sub="$activity->causer->email" />
                            @else
                                <span class="text-muted small">sistema</span>
                            @endif
                        </td>
                        <td>
                            @switch($activity->event)
                                @case('created') <x-badge color="success" icon="fas fa-plus">Criação</x-badge> @break
                                @case('updated') <x-badge color="info" icon="fas fa-pen">Alteração</x-badge> @break
                                @case('deleted') <x-badge color="danger" icon="fas fa-trash">Exclusão</x-badge> @break
                                @default <x-badge color="muted">{{ $activity->event ?? $activity->description }}</x-badge>
                            @endswitch
                        </td>
                        <td>
                            <div><i class="{{ $objectIcon }} text-muted mr-1"></i><strong>{{ $objectLabel }}</strong> #{{ $activity->subject_id }}</div>
                            @if ($activity->subject?->name)
                                <div class="small text-muted">{{ $activity->subject->name }}</div>
                            @endif
                        </td>
                        <td class="small">
                            @foreach ($new as $campo => $valor)
                                <div>
                                    <span class="text-muted">{{ $campo }}:</span>
                                    @if (array_key_exists($campo, $old))
                                        <del class="text-muted">{{ $format($old[$campo]) }}</del> →
                                    @endif
                                    <strong>{{ $format($valor) }}</strong>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif

        @if ($activities->hasPages())
            <x-slot:footer>{{ $activities->links() }}</x-slot:footer>
        @endif
    </x-card>
@endsection

{{--
    Card de filtros (GET), recolhível. Começa aberto quando algum filtro está preenchido.
    <x-filter-card :action="route('admin.regras.index')"> campos (x-form.*) </x-filter-card>
--}}
@props([
    'action',
    'title' => 'Filtros',
    'keys' => ['filter', 'situacao'],
])

@php
    $active = collect($keys)
        ->map(fn ($key) => request()->query($key))
        ->flatten()
        ->filter(fn ($value) => filled($value))
        ->count();
@endphp

<form method="GET" action="{{ $action }}" data-submit-once="false">
    <x-card :title="$title" icon="fas fa-filter" :collapsible="true" :collapsed="$active === 0">
        <x-slot:tools>
            @if ($active > 0)
                <x-badge color="accent" :dot="false">{{ $active }} {{ $active === 1 ? 'filtro ativo' : 'filtros ativos' }}</x-badge>
            @endif
        </x-slot:tools>

        <div class="form-row">
            {{ $slot }}
        </div>

        <x-slot:footer>
            <div class="d-flex flex-wrap" style="gap: .5rem;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search mr-1"></i> Filtrar</button>
                <a href="{{ $action }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-eraser mr-1"></i> Limpar</a>
            </div>
        </x-slot:footer>
    </x-card>
</form>

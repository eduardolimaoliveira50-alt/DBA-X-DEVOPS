{{-- Selo do ambiente (Homologação, Local...). Produção não mostra nada. --}}
@php($ambiente = config('sistema.ambientes')[app()->environment()] ?? null)

@if ($ambiente)
    <span {{ $attributes->merge(['class' => 'topbar-env-badge']) }} title="Ambiente de {{ mb_strtolower($ambiente) }}: os dados aqui não são de produção">
        {{ $ambiente }}
    </span>
@endif

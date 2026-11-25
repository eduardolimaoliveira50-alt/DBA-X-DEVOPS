{{-- Botão "Novo ..." (abre a tela de cadastro). <x-btn.new :route="..." title="Novo sistema" can="sistemas.gerenciar" /> --}}
@props([
    'route',
    'title' => 'Novo',
    'icon' => 'fas fa-plus',
    'can' => null,
])

@if (! $can || auth()->user()?->can($can))
    <a href="{{ $route }}" {{ $attributes->merge(['class' => 'btn btn-primary btn-sm']) }}>
        <i class="{{ $icon }} mr-1"></i> {{ $title }}
    </a>
@endif

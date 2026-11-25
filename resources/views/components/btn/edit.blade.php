{{-- Botão "Editar". <x-btn.edit :route="..." can="sistemas.gerenciar" /> --}}
@props([
    'route',
    'label' => 'Editar',
    'can' => null,
])

@if (! $can || auth()->user()?->can($can))
    <a href="{{ $route }}" {{ $attributes->merge(['class' => 'btn btn-outline-primary btn-sm']) }}>
        <i class="fas fa-pen mr-1"></i> {{ $label }}
    </a>
@endif

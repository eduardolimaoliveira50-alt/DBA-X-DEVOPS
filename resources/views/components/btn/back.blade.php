{{-- Botão "Voltar". <x-btn.back :route="route('admin.sistemas.index')" /> --}}
@props([
    'route',
    'label' => 'Voltar',
])

<a href="{{ $route }}" {{ $attributes->merge(['class' => 'btn btn-outline-secondary']) }}>
    <i class="fas fa-arrow-left mr-1"></i> {{ $label }}
</a>

{{--
    Selo arredondado em cor suave.
    <x-badge color="success" icon="fas fa-check">Ativa</x-badge>
    Cores: primary, accent, success, warning, danger, info, muted.
--}}
@props([
    'color' => 'primary',
    'icon' => null,
    'dot' => true,
])

<span {{ $attributes->merge(['class' => 'badge-pill-soft badge-pill-soft--'.$color.(($icon || ! $dot) ? ' no-dot' : '')]) }}>
    @if ($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
</span>

{{--
    Estado vazio com ícone, título, texto e ações.
    <x-empty-state icon="fas fa-desktop" title="Nenhum sistema">
        texto
        <x-slot:actions> botões </x-slot:actions>
    </x-empty-state>
--}}
@props([
    'icon' => 'fas fa-inbox',
    'title',
])

<div {{ $attributes->merge(['class' => 'ca-empty']) }}>
    <span class="ca-empty__icon"><i class="{{ $icon }}"></i></span>
    <div class="ca-empty__title">{{ $title }}</div>
    @if ($slot->isNotEmpty())
        <p class="ca-empty__text">{{ $slot }}</p>
    @endif
    @isset($actions)
        <div class="mt-3 d-flex justify-content-center flex-wrap" style="gap: .5rem;">
            {{ $actions }}
        </div>
    @endisset
</div>

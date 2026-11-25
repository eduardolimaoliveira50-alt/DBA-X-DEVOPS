{{--
    Card "glass" padrão do painel.
    <x-card title="Regras" icon="fas fa-key" :count="$total" accent="primary" :no-padding="true">
        <x-slot:tools> botões no cabeçalho </x-slot:tools>
        conteúdo
        <x-slot:footer> rodapé </x-slot:footer>
    </x-card>
--}}
@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'count' => null,
    'accent' => null,
    'noPadding' => false,
    'collapsible' => false,
    'collapsed' => false,
])

@php
    $classes = 'card';
    if ($accent) {
        $classes .= ' card-accent card-accent--'.$accent;
    }
    if ($collapsed) {
        $classes .= ' collapsed-card';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if ($title || isset($tools))
        <div class="card-header d-flex flex-wrap align-items-center"
             @if ($collapsible) data-card-widget="collapse" style="cursor: pointer;" @endif>
            {{-- o título encolhe (min-width: 0) para os botões do card ficarem na mesma linha no celular --}}
            <div class="d-flex align-items-center mr-auto py-1" style="flex: 1 1 0; min-width: 0;">
                @if ($icon)
                    <span class="card-icon"><i class="{{ $icon }}"></i></span>
                @endif
                <div>
                    <h3 class="card-title mb-0">{{ $title }}</h3>
                    @if ($subtitle)
                        <div class="small text-muted">{{ $subtitle }}</div>
                    @endif
                </div>
                @if (! is_null($count))
                    <span class="card-count">{{ $count }}</span>
                @endif
            </div>

            <div class="card-tools d-flex align-items-center flex-wrap" style="gap: .4rem;">
                {{ $tools ?? '' }}
                @if ($collapsible)
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" aria-label="Recolher ou expandir">
                        <i class="fas {{ $collapsed ? 'fa-plus' : 'fa-minus' }}"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif

    <div class="card-body {{ $noPadding ? 'p-0' : '' }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endisset
</div>

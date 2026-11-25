{{--
    Cabeçalho da página (vai no @section('content_header')).
    <x-page-header title="Regras de acesso" subtitle="..." icon="fas fa-key" :crumbs="['Gestão', 'Regras']">
        <x-slot:aside> botões </x-slot:aside>
    </x-page-header>
--}}
@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'crumbs' => [],
])

<div class="page-header-card">
    <div>
        @if ($crumbs)
            <x-breadcrumb :items="$crumbs" />
        @endif
        <div class="page-header-card__main">
            @if ($icon)
                <span class="page-header-card__icon"><i class="{{ $icon }}"></i></span>
            @endif
            <div>
                <h1 class="page-header-card__title">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="page-header-card__subtitle">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>

    @isset($aside)
        <div class="page-header-card__aside">
            {{ $aside }}
        </div>
    @endisset
</div>

{{--
    Indicador em destaque (tela inicial da gestão).
    <x-kpi label="Regras vigentes" :value="12" icon="fas fa-key" color="primary" hint="..." :href="$url" />
    Cores: primary, accent, success, danger, info, slate.
--}}
@props([
    'label',
    'value',
    'icon' => 'fas fa-chart-line',
    'color' => 'primary',
    'hint' => null,
    'href' => null,
])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'ca-kpi ca-kpi--'.$color.' d-block']) }}>
@else
    <div {{ $attributes->merge(['class' => 'ca-kpi ca-kpi--'.$color]) }}>
@endif
    <span class="ca-kpi__icon"><i class="{{ $icon }}"></i></span>
    <div class="ca-kpi__label">{{ $label }}</div>
    <div class="ca-kpi__value">{{ $value }}</div>
    @if ($hint)
        <div class="ca-kpi__hint">{{ $hint }}</div>
    @endif
@if ($href)
    </a>
@else
    </div>
@endif

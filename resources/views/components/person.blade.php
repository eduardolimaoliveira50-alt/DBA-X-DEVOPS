{{-- Nome com avatar de inicial e uma linha de apoio (e-mail, papel). --}}
@props([
    'name',
    'sub' => null,
])

@php($initial = mb_strtoupper(mb_substr(trim((string) $name), 0, 1)) ?: '?')

<span {{ $attributes->merge(['class' => 'ca-person']) }}>
    <span class="ca-person__avatar" aria-hidden="true">{{ $initial }}</span>
    <span>
        <span class="ca-person__name d-block">{{ $name }}</span>
        @if ($sub)
            <span class="ca-person__sub d-block">{{ $sub }}</span>
        @endif
    </span>
</span>

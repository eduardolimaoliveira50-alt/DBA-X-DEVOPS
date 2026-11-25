{{-- Texto técnico (host, IP, URL) com botão de copiar. <x-code value="MAT-00001" /> --}}
@props([
    'value',
    'copy' => true,
])

<span {{ $attributes->merge(['class' => 'ca-code']) }}>
    <span>{{ $value }}</span>
    @if ($copy && filled($value))
        <button type="button" class="ca-code__copy" data-copy="{{ $value }}" data-toggle="tooltip" title="Copiar" aria-label="Copiar {{ $value }}">
            <i class="far fa-copy"></i>
        </button>
    @endif
</span>

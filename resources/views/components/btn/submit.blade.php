{{--
    Botão de envio. Mostra "carregando" sozinho ao enviar (submit-once.js).
    <x-btn.submit label="Salvar" icon="fas fa-check" />
--}}
@props([
    'label' => 'Salvar',
    'icon' => 'fas fa-check',
    'variant' => 'primary',
])

<button type="submit" {{ $attributes->merge(['class' => 'btn btn-'.$variant]) }}>
    @if ($icon)
        <i class="{{ $icon }} mr-1"></i>
    @endif
    {{ $label }}
</button>

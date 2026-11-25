{{--
    Ação que muda dados (PATCH/DELETE) num botão, com confirmação SweetAlert antes de enviar.
    <x-btn.confirm :route="route('admin.regras.cancelar', $regra)" method="PATCH"
                   label="Cancelar" icon="fas fa-times" variant="soft-danger"
                   confirm="Cancelar este pedido?" confirm-title="Cancelar pedido"
                   confirm-button="Sim, cancelar" confirm-variant="danger" />
    Sem "confirm", envia direto.
--}}
@props([
    'route',
    'method' => 'POST',
    'label',
    'icon' => null,
    'variant' => 'outline-primary',
    'confirm' => null,
    'confirmTitle' => null,
    'confirmButton' => null,
    'confirmVariant' => 'primary',
    'tooltip' => null,
])

<form method="POST" action="{{ $route }}" class="d-inline-block"
      @if ($confirm)
          data-confirm="{{ $confirm }}"
          @if ($confirmTitle) data-confirm-title="{{ $confirmTitle }}" @endif
          @if ($confirmButton) data-confirm-button="{{ $confirmButton }}" @endif
          data-confirm-variant="{{ $confirmVariant }}"
      @endif>
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method(strtoupper($method))
    @endif
    <button type="submit" {{ $attributes->merge(['class' => 'btn btn-sm btn-'.$variant]) }}
            @if ($tooltip) data-toggle="tooltip" title="{{ $tooltip }}" @endif>
        @if ($icon)
            <i class="{{ $icon }} mr-1"></i>
        @endif
        {{ $label }}
    </button>
</form>

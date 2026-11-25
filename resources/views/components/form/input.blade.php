{{--
    Campo de texto (Bootstrap 4) com rótulo, ícone opcional, ajuda e erro de validação.
    <x-form.input name="address" label="Endereço" col="6" :value="$sistema->address"
                  icon="fas fa-globe" help="..." required />
    - "name" pode ser de lista (filter[ip]): o old() e o erro usam a forma com ponto.
    - Atributos extras (placeholder, autofocus, maxlength...) vão para o <input>.
--}}
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'col' => null,
    'icon' => null,
    'help' => null,
    'required' => false,
])

@php
    $key = trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.');
    $id = 'f-'.str_replace('.', '-', $key);
    $error = $errors->first($key);
    $current = $type === 'password' ? null : old($key, $value);
@endphp

<div class="form-group {{ $col ? 'col-md-'.$col : '' }}">
    @if ($label)
        <label for="{{ $id }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    @if ($icon)
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="{{ $icon }}"></i></span>
            </div>
    @endif

    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $current }}"
           @required($required)
           @if ($error) aria-invalid="true" @endif
           @if ($help) aria-describedby="{{ $id }}-ajuda" @endif
           {{ $attributes->merge(['class' => 'form-control'.($error ? ' is-invalid' : '')]) }}>

    @if ($icon)
            @if ($error)
                <div class="invalid-feedback">{{ $error }}</div>
            @endif
        </div>
    @elseif ($error)
        <div class="invalid-feedback">{{ $error }}</div>
    @endif

    @if ($help)
        <small id="{{ $id }}-ajuda" class="form-text text-muted">{{ $help }}</small>
    @endif
</div>

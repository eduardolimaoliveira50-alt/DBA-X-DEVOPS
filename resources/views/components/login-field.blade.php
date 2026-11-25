{{--
    Campo do login (ícone à esquerda; senha com botão de mostrar).
    <x-login-field name="email" label="E-mail" icon="fas fa-envelope" type="email" autofocus />
--}}
@props([
    'name',
    'label',
    'icon' => null,
    'type' => 'text',
    'value' => null,
])

@php
    $id = 'campo-'.$name;
    $error = $errors->first($name);
@endphp

<div class="login-field">
    <label for="{{ $id }}">{{ $label }}</label>
    <div class="login-input-wrap">
        @if ($icon)
            <span class="login-input-icon"><i class="{{ $icon }}"></i></span>
        @endif
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
               value="{{ $type === 'password' ? '' : old($name, $value) }}"
               @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-erro" @endif
               {{ $attributes->merge(['class' => $error ? 'has-error' : '']) }}>
        @if ($type === 'password')
            <button type="button" class="login-password-toggle" data-password-toggle="#{{ $id }}"
                    aria-label="Mostrar senha" title="Mostrar senha" aria-pressed="false">
                <i class="far fa-eye"></i>
            </button>
        @endif
    </div>
    {{-- a mensagem aparece na caixa de erro do topo do formulário; aqui o campo só fica vermelho --}}
    @if ($error)
        <span class="sr-only" id="{{ $id }}-erro">{{ $error }}</span>
    @endif
</div>

{{--
    Lista de opções (Bootstrap 4).
    <x-form.select name="legacy_system_id" label="Sistema" :options="$sistemas->pluck('name', 'id')"
                   :selected="..." placeholder="Todos" col="4" />
    - options: [valor => texto]. "placeholder" vira a primeira opção, com valor vazio.
--}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'col' => null,
    'icon' => null,
    'help' => null,
    'required' => false,
])

@php
    $key = trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.');
    $id = 'f-'.str_replace('.', '-', $key);
    $error = $errors->first($key);
    $current = (string) old($key, $selected);
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

    <select name="{{ $name }}" id="{{ $id }}" @required($required)
            @if ($error) aria-invalid="true" @endif
            {{ $attributes->merge(['class' => 'custom-select'.($error ? ' is-invalid' : '')]) }}>
        @if (! is_null($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected($current !== '' && $current === (string) $value)>{{ $text }}</option>
        @endforeach
    </select>

    @if ($icon)
            @if ($error)
                <div class="invalid-feedback">{{ $error }}</div>
            @endif
        </div>
    @elseif ($error)
        <div class="invalid-feedback">{{ $error }}</div>
    @endif

    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
</div>

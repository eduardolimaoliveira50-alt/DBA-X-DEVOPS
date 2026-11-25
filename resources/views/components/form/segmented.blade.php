{{--
    Escolha única em pílulas (rádios estilizados).
    <x-form.segmented name="subject_type" label="Liberar para" selected="ip"
                      :options="['ip' => ['label' => 'Um IP', 'icon' => 'fas fa-desktop'], ...]" />
    Combina com data-show-when="subject_type=ip|cidr" para mostrar os campos de cada opção.
--}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'col' => null,
])

@php
    $current = (string) old($name, $selected);
    $error = $errors->first($name);
@endphp

<div class="form-group {{ $col ? 'col-md-'.$col : '' }}">
    @if ($label)
        <label class="d-block">{{ $label }}</label>
    @endif

    <div class="ca-segmented" role="radiogroup" @if ($label) aria-label="{{ $label }}" @endif>
        @foreach ($options as $value => $option)
            @php($id = 'f-'.$name.'-'.$value)
            <input type="radio" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}" @checked($current === (string) $value)>
            <label for="{{ $id }}">
                @if (! empty($option['icon']))
                    <i class="{{ $option['icon'] }}"></i>
                @endif
                {{ $option['label'] ?? $value }}
            </label>
        @endforeach
    </div>

    @if ($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
</div>

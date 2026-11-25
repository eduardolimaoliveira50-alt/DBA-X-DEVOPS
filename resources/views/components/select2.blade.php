{{-- Ver App\View\Components\Select2. O select2 é ligado por resources/js/components/forms/select2-global.js. --}}
@php($error = $errors->first($name))

<div class="form-group {{ $col ? 'col-md-'.$col : '' }}">
    @if ($label)
        <label for="{{ $id }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <select id="{{ $id }}" name="{{ $name }}" @required($required)
            @if ($error) aria-invalid="true" @endif
            {{ $attributes->merge(['class' => 'form-control'.($error ? ' is-invalid' : '')]) }}
            data-svo-select2="1" data-select2-url="{{ route('select2.list', ['model' => $model]) }}"
            data-placeholder="{{ $placeholder }}" data-theme="bootstrap4" style="width: 100%;">
        <option value=""></option>
        @foreach ($selectedOptions as $option)
            <option value="{{ $option['id'] }}" selected>{{ $option['text'] }}</option>
        @endforeach
    </select>

    @if ($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif

    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
</div>

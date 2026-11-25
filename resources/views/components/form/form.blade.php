{{--
    Formulário com CSRF e método (PUT/PATCH/DELETE viram campo _method).
    <x-form.form :action="route('admin.sistemas.update', $sistema)" method="PUT"> ... </x-form.form>
--}}
@props([
    'action',
    'method' => 'POST',
])

@php($method = strtoupper($method))

<form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" {{ $attributes }}>
    @if ($method !== 'GET')
        @csrf
    @endif
    @if (! in_array($method, ['GET', 'POST'], true))
        @method($method)
    @endif

    {{ $slot }}
</form>

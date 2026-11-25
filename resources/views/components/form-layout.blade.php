{{--
    Molde das telas de formulário: o formulário em 2/3 e um card de apoio em 1/3, com a mesma altura.
    No celular e no tablet (abaixo de lg), um embaixo do outro, em largura total.
    <x-form-layout>
        <x-form.form ...><x-card ...>campos</x-card></x-form.form>
        <x-slot:aside><x-card title="Como funciona">...</x-card></x-slot:aside>
    </x-form-layout>
--}}
<div class="row ca-cols-stretch">
    <div class="{{ isset($aside) ? 'col-lg-8' : 'col-12' }}">
        {{ $slot }}
    </div>

    @isset($aside)
        <div class="col-lg-4">
            {{ $aside }}
        </div>
    @endisset
</div>

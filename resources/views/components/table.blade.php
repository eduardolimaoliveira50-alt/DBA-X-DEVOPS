{{--
    Tabela padrão (responsiva, com destaque ao passar o mouse).
    <x-table>
        <x-slot:head> <tr><th>...</th></tr> </x-slot:head>
        linhas
    </x-table>
--}}
<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-hover']) }}>
        @isset($head)
            <thead>
                {{ $head }}
            </thead>
        @endisset
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>

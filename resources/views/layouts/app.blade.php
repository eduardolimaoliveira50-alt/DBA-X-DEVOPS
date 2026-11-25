{{--
    Layout do painel (AdminLTE). As telas usam:
      @section('title')           título da aba e do chip do topo
      @section('content_header')  <x-page-header ...>
      @section('content')         conteúdo
    Avisos (flash) e confirmações: resources/js/components/alerts.js.
--}}
@extends('adminlte::page')

@section('meta_tags')
    {{-- Aplica o tema salvo ANTES da pintura (sem "piscar"); o resto fica no theme-toggle.js --}}
    <script>
        (function () {
            try {
                var tema = localStorage.getItem('almoxarifado-theme');
                if (tema !== 'light' && tema !== 'dark') {
                    tema = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                document.documentElement.setAttribute('data-theme', tema);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    <meta name="color-scheme" content="light dark">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    {{-- a fonte (Inter) vai no pacote do Vite: nada é baixado de fora da rede --}}
@stop

@section('adminlte_css')
    @parent
    @vite(['resources/js/app.js'])
@stop

@section('footer')
    <x-footer />
@stop

@section('js')
    <x-flash-messages />
@stop

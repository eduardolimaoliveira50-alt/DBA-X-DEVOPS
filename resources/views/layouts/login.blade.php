{{--
    Layout do login e das páginas de erro (sem AdminLTE).
      @section('title')       título da aba
      @section('heading')     título do cartão (padrão: nome do sistema)
      @section('subheading')  subtítulo do cartão (padrão: descrição do sistema)
      @section('content')     corpo do cartão
      @section('card_class')  classe extra do cartão (ex.: login-card--largo para formulário longo)
      @section('apos_pagina') conteúdo fora da página (ex.: folha de impressão, escondida na tela)
--}}
@php($ambiente = config('sistema.ambientes')[app()->environment()] ?? null)
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- páginas de erro podem ser montadas sem sessão (ex.: 404 de rota inexistente) --}}
    <meta name="csrf-token" content="{{ rescue(fn () => csrf_token(), '', false) }}">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', 'Entrar') · {{ config('sistema.nome') }}</title>
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
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    @vite(['resources/js/login.js'])
</head>
<body>
    <div class="login-page">
        <div class="bg-overlay" aria-hidden="true"></div>
        <div class="bg-pattern" aria-hidden="true"></div>
        <div class="deco-circle c1" aria-hidden="true"></div>
        <div class="deco-circle c2" aria-hidden="true"></div>

        <button type="button" class="login-theme-toggle" data-theme-toggle aria-label="Alternar tema" title="Alternar tema">
            <i class="fas fa-moon theme-toggle-btn__moon"></i>
            <i class="fas fa-sun theme-toggle-btn__sun"></i>
        </button>

        <main class="page">
            <div class="login-card @yield('card_class')">
                <div class="login-header">
                    <div class="login-brand-mark" aria-hidden="true"><i class="{{ config('sistema.icone') }}"></i></div>
                    <h1>@yield('heading', config('sistema.nome'))</h1>
                    <p>@yield('subheading', config('sistema.descricao'))</p>
                    @if ($ambiente)
                        <span class="login-env"><i class="fas fa-flask"></i> {{ $ambiente }}</span>
                    @endif
                </div>

                <div class="login-body">
                    @yield('content')
                </div>
            </div>

            <footer class="login-footer">
                <strong>{{ config('sistema.nome') }}</strong> · {{ config('sistema.orgao') }} · {{ config('sistema.unidade') }}
                © {{ date('Y') }}<br>
                versão {{ config('sistema.versao') }}
            </footer>
        </main>
    </div>

    @yield('apos_pagina')
</body>
</html>

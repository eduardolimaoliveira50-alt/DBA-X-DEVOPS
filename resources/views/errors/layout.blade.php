{{--
    Base das páginas de erro: mesmo cartão do login.
      @section('code')     número do erro
      @section('message')  explicação em português
      @section('actions')  opcional: troca os botões padrão
--}}
@extends('layouts.login')

@section('content')
    <div class="error-body">
        <div class="error-code">@yield('code')</div>
        <p class="error-text">@yield('message')</p>
        <div class="error-actions">
            @hasSection('actions')
                @yield('actions')
            @else
                <a class="primary" href="{{ url('/') }}"><i class="fas fa-home"></i> Ir para o início</a>
                <a class="secondary" href="#" onclick="history.back(); return false;"><i class="fas fa-arrow-left"></i> Voltar</a>
            @endif
        </div>
    </div>
@endsection

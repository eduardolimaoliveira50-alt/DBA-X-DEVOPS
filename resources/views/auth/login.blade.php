@extends('layouts.login')

@section('title', 'Entrar')

@section('content')
    <div class="step-badge"><i class="fas fa-lock"></i> Acesso ao portal</div>

    @if ($errors->any())
        <div class="login-error-box" role="alert">
            <i class="fas fa-times-circle"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-login-field name="email" label="E-mail" icon="fas fa-envelope" type="email"
                       autocomplete="username" placeholder="nome@almoxarifado.test" required autofocus />

        <x-login-field name="password" label="Senha" icon="fas fa-key" type="password"
                       autocomplete="current-password" placeholder="••••••••" required />

        <div class="login-row">
            <label class="login-check">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                Manter conectado neste computador
            </label>
        </div>

        <button type="submit" class="login-btn-entrar">
            <span class="btn-spinner" aria-hidden="true"></span>
            Entrar
            <i class="fas fa-arrow-right"></i>
        </button>

        @if (config('sistema.demo'))
            <div class="login-security-note">
                <i class="fas fa-flask"></i>
                <span>Demonstração (dados fictícios): <strong>admin@almoxarifado.test</strong>, <strong>almoxarife@almoxarifado.test</strong>
                    ou <strong>consulta@almoxarifado.test</strong>, senha <strong>almox2026</strong>.</span>
            </div>
        @endif

        <div class="login-security-note">
            <i class="fas fa-shield-alt"></i>
            <span>Acesso registrado. As contas são criadas pela gestão do sistema.</span>
        </div>
    </form>
@endsection

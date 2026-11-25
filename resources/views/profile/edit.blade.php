{{-- "Meu perfil": os próprios dados (só leitura) e a troca da própria senha. --}}
@extends('layouts.app')

@section('title', 'Meu perfil')

@section('content_header')
    <x-page-header title="Meu perfil" icon="fas fa-user-circle" :crumbs="['Meu perfil']"
                   subtitle="Seus dados de acesso e a troca da sua senha." />
@endsection

@section('content')
    <x-form-layout>
        <x-form.form :action="route('password.update')" method="PUT">
            <x-card title="Trocar a senha" icon="fas fa-key" accent="primary"
                    subtitle="Use ao menos 8 caracteres, com letras e números.">
                @php($erros = $errors->updatePassword)
                <div class="form-row">
                    <x-form.input name="current_password" type="password" label="Senha atual" col="12" icon="fas fa-lock"
                                  autocomplete="current-password" required />
                    <x-form.input name="password" type="password" label="Senha nova" col="6" icon="fas fa-key"
                                  autocomplete="new-password" required />
                    <x-form.input name="password_confirmation" type="password" label="Repita a senha nova" col="6" icon="fas fa-key"
                                  autocomplete="new-password" required />
                </div>
                @if ($erros->any())
                    <div class="alert alert-danger mb-0" role="alert">
                        @foreach ($erros->all() as $erro)
                            <div>{{ $erro }}</div>
                        @endforeach
                    </div>
                @endif

                <x-slot:footer>
                    <x-btn.submit label="Salvar a senha nova" />
                </x-slot:footer>
            </x-card>
        </x-form.form>

        <x-slot:aside>
            <x-card title="Seus dados" icon="fas fa-id-card" class="h-100">
                <dl class="mb-0 small">
                    <dt class="text-muted">Nome</dt>
                    <dd class="mb-3">{{ $user->name }}</dd>
                    <dt class="text-muted">E-mail (login)</dt>
                    <dd class="mb-3">{{ $user->email }}</dd>
                    <dt class="text-muted">Perfil</dt>
                    <dd class="mb-3">
                        @forelse ($user->getRoleNames() as $perfil)
                            <x-role-badge :role="$perfil" />
                        @empty
                            —
                        @endforelse
                    </dd>
                </dl>
                <p class="small text-muted mb-0">Para mudar nome, e-mail ou perfil, fale com o administrador.</p>
            </x-card>
        </x-slot:aside>
    </x-form-layout>
@endsection

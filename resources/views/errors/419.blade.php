@extends('errors.layout')

@section('title', 'Sessão expirada')
@section('heading', 'Sessão expirada')
@section('subheading', 'Por segurança, o formulário perdeu a validade')
@section('code', '419')
@section('message', 'A página ficou aberta por muito tempo sem uso. Abra de novo e repita a operação; se for o login, é só entrar outra vez.')

@section('actions')
    <a class="primary" href="{{ url('/') }}"><i class="fas fa-redo"></i> Abrir de novo</a>
@endsection

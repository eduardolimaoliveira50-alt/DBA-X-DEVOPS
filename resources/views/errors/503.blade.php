@extends('errors.layout')

@section('title', 'Em manutenção')
@section('heading', 'Em manutenção')
@section('subheading', 'O sistema volta em instantes')
@section('code', '503')
@section('message', 'Estamos publicando uma atualização. Aguarde alguns minutos e recarregue a página.')

@section('actions')
    <a class="primary" href="#" onclick="location.reload(); return false;"><i class="fas fa-redo"></i> Recarregar</a>
@endsection

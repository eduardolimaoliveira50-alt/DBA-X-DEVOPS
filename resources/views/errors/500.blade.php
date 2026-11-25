@extends('errors.layout')

@section('title', 'Erro no sistema')
@section('heading', 'Algo deu errado')
@section('subheading', 'O erro foi registrado no servidor')
@section('code', '500')
@section('message', 'Não foi possível concluir a operação. Tente de novo em instantes; se continuar, avise o administrador do sistema com o horário em que aconteceu.')

@extends('layouts.app')

@php($editing = $fornecedor->exists)

@section('title', $editing ? 'Editar fornecedor' : 'Novo fornecedor')

@section('content_header')
    <x-page-header :title="$editing ? 'Editar '.$fornecedor->razao_social : 'Novo fornecedor'" icon="fas fa-truck"
                   :crumbs="['Estoque', 'Fornecedores' => route('admin.fornecedores.index'), $editing ? 'Editar' : 'Novo']"
                   subtitle="O fornecedor aparece na lista ao registrar uma entrada de material." />
@endsection

@section('content')
    <x-form-layout>
        <x-form.form :action="$editing ? route('admin.fornecedores.update', $fornecedor) : route('admin.fornecedores.store')"
                     :method="$editing ? 'PUT' : 'POST'">
            <x-card title="Dados do fornecedor" icon="fas fa-pen" accent="primary">
                <div class="form-row">
                    <x-form.input name="razao_social" label="Razão social" col="7" icon="fas fa-building" :value="$fornecedor->razao_social"
                                  placeholder="Distribuidora Pantanal Ltda" maxlength="150" required autofocus />
                    <x-form.input name="cnpj" label="CNPJ" col="5" icon="fas fa-id-card"
                                  :value="\App\Support\Cnpj::format($fornecedor->cnpj)"
                                  placeholder="00.000.000/0000-00" maxlength="18" inputmode="numeric" autocomplete="off" data-mask="cnpj" />
                </div>
                <div class="form-row">
                    <x-form.input name="email" label="E-mail" col="7" type="email" icon="fas fa-envelope" :value="$fornecedor->email"
                                  placeholder="vendas@fornecedor.com.br" maxlength="150" />
                    <x-form.input name="telefone" label="Telefone" col="5" icon="fas fa-phone" :value="$fornecedor->telefone"
                                  placeholder="(65) 3333-0000" maxlength="15" data-mascara="telefone" />
                </div>

                <x-slot:footer>
                    <div class="d-flex flex-wrap" style="gap: .5rem;">
                        <x-btn.submit :label="$editing ? 'Salvar alterações' : 'Cadastrar fornecedor'" />
                        <x-btn.back :route="route('admin.fornecedores.index')" />
                    </div>
                </x-slot:footer>
            </x-card>
        </x-form.form>

        <x-slot:aside>
            <x-card title="Como funciona" icon="fas fa-info-circle">
                <div class="ca-callout mb-3">
                    <i class="fas fa-id-card"></i>
                    <div>O CNPJ é conferido (dígitos verificadores). Digite com ou sem pontuação.</div>
                </div>
                <div class="ca-callout ca-callout--accent">
                    <i class="fas fa-lightbulb"></i>
                    <div>Fornecedores do cadastro antigo podem estar sem CNPJ: complete quando editar.</div>
                </div>
            </x-card>
        </x-slot:aside>
    </x-form-layout>
@endsection

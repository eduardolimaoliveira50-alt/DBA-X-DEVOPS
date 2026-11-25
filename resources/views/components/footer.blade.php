{{-- Rodapé do painel: identificação do sistema, órgão, versão e ambiente (compacto no celular). --}}
<footer class="main-footer app-footer">
    <span class="app-footer__brand">
        <i class="{{ config('sistema.icone') }}"></i>
        <span>
            <strong>{{ config('sistema.nome') }}</strong>
            <span class="d-none d-lg-inline">· {{ config('sistema.descricao') }}</span>
        </span>
    </span>
    <span class="app-footer__meta">
        <span class="d-none d-sm-inline">{{ config('sistema.orgao') }} · {{ config('sistema.unidade') }} © {{ date('Y') }}</span>
        <span class="app-footer__sep d-none d-md-inline">|</span>
        <span class="d-none d-md-inline">versão {{ config('sistema.versao') }}</span>
        <x-env-badge />
    </span>
</footer>

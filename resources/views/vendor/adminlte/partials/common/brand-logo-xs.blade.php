{{-- Marca do sistema na sidebar (sobrescreve o do AdminLTE): ícone + nome, de config/sistema.php. --}}
@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

<a href="{{ $dashboard_url }}"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand {{ config('adminlte.classes_brand') }}"
    @else
        class="brand-link {{ config('adminlte.classes_brand') }}"
    @endif
    title="{{ config('sistema.nome') }}">

    <span class="brand-mark" aria-hidden="true"><i class="{{ config('sistema.icone') }}"></i></span>

    <span class="brand-text-stack">
        <span class="brand-text-stack__title">{{ config('sistema.nome') }}</span>
        <span class="brand-text-stack__subtitle">{{ config('sistema.orgao') }} · {{ config('sistema.unidade') }}</span>
    </span>
</a>

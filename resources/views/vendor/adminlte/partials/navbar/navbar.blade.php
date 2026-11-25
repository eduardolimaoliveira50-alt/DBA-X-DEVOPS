{{--
    Barra superior (sobrescreve a do AdminLTE, no padrão do SVO/FAMA):
    página atual, selo de ambiente, data/hora, botão de tema e menu do usuário.
--}}
@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php($currentPageTitle = trim(View::yieldContent('title')) ?: config('sistema.nome'))
@php($currentDateTime = now()->format('d/m/Y H:i'))

<nav class="main-header navbar
    topbar-glass
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Esquerda --}}
    <ul class="navbar-nav">
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        @yield('content_top_nav_left')

        <li class="nav-item d-none d-md-flex align-items-center">
            <div class="topbar-context-chip">
                <span class="topbar-context-chip__eyebrow">{{ config('sistema.nome') }}</span>
                <span class="topbar-context-chip__title">{{ $currentPageTitle }}</span>
            </div>
        </li>
    </ul>

    {{-- Direita --}}
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-flex align-items-center mr-2">
            <x-env-badge />
        </li>

        <li class="nav-item d-none d-lg-flex align-items-center mr-2">
            <div class="topbar-meta-pill">
                <span class="topbar-meta-pill__label">Agora</span>
                <span class="topbar-meta-pill__value" data-topbar-datetime>{{ $currentDateTime }}</span>
            </div>
        </li>

        <li class="nav-item d-flex align-items-center mr-2">
            <button type="button" class="nav-link theme-toggle-btn" data-theme-toggle
                    aria-label="Alternar tema" title="Alternar tema">
                <i class="fas fa-moon theme-toggle-btn__moon"></i>
                <i class="fas fa-sun theme-toggle-btn__sun"></i>
            </button>
        </li>

        @yield('content_top_nav_right')

        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>

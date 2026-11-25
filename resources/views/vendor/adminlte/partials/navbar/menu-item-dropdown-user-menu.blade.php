{{-- Menu do usuário (sobrescreve o do AdminLTE): inicial, nome, e-mail, perfis e "Sair". --}}
{{-- um único bloco PHP: misturar a forma de uma linha com a de bloco confunde o compilador do Blade --}}
@php
    $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout');
    if ($logout_url) {
        $logout_url = config('adminlte.use_route_url', false) ? route($logout_url) : url($logout_url);
    }

    $usuario = Auth::user();
    $inicial = mb_strtoupper(mb_substr(trim($usuario->name), 0, 1));
    $primeiroNome = \Illuminate\Support\Str::before(trim($usuario->name), ' ');
    $perfis = method_exists($usuario, 'getRoleNames') ? $usuario->getRoleNames() : collect();
@endphp

<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle usermenu-trigger-glass" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="usermenu-trigger__avatar" aria-hidden="true">{{ $inicial }}</span>
        <span class="d-none d-md-inline">{{ $primeiroNome }}</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 shadow usermenu-panel">
        <li class="usermenu-panel__header text-white">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center mr-3 font-weight-bold usermenu-panel__avatar">
                    {{ $inicial }}
                </div>
                <div class="min-w-0">
                    <div class="font-weight-bold usermenu-panel__name">{{ $usuario->name }}</div>
                    <div class="usermenu-panel__subtitle text-truncate">{{ $usuario->email }}</div>
                    @if ($perfis->isNotEmpty())
                        <div class="usermenu-panel__roles">
                            @foreach ($perfis as $perfil)
                                <span class="usermenu-panel__role">{{ \App\Support\Perfis::ROLE_LABELS[$perfil] ?? $perfil }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </li>

        @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item')

        <li class="usermenu-panel__actions">
            <a class="btn btn-block d-flex align-items-center justify-content-center mb-2 usermenu-panel__btn usermenu-panel__btn--light"
               href="{{ route('profile.edit') }}">
                <i class="fas fa-fw fa-user-circle mr-1"></i>
                Meu perfil
            </a>
            <a class="btn btn-block d-flex align-items-center justify-content-center usermenu-panel__btn usermenu-panel__btn--danger"
               href="#"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off mr-1"></i>
                Sair
            </a>

            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
</li>

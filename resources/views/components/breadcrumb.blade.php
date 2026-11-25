{{--
    Trilha de navegação. Itens: ['Gestão', 'Regras'] (só texto) ou ['Sistemas' => url] (com link).
    O primeiro item é sempre "Início".
--}}
@props(['items' => []])

<nav aria-label="Trilha de navegação">
    <ol class="breadcrumb breadcrumb-glass">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Início</a></li>
        @foreach ($items as $label => $url)
            @if (is_string($label))
                <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
            @else
                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" @if ($loop->last) aria-current="page" @endif>{{ $url }}</li>
            @endif
        @endforeach
    </ol>
</nav>

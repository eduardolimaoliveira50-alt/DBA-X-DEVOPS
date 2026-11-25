{{--
    Mensagens do servidor para o alerts.js (avisos SweetAlert no canto da tela).
    session('status') é tratado como sucesso; erros de validação viram um aviso de erro.
--}}
@php
    $messages = array_filter([
        'success' => session('success') ?? session('status'),
        'error' => session('error') ?? ($errors->any() ? 'Confira os campos destacados: '.$errors->first() : null),
        'warning' => session('warning'),
        'info' => session('info'),
    ]);
@endphp

@if ($messages)
    <script type="application/json" id="flash-messages">@json($messages)</script>
@endif

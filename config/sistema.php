<?php

/*
|--------------------------------------------------------------------------
| Identificação do sistema
|--------------------------------------------------------------------------
|
| Nome, órgão e versão mostrados no menu, no login, nas páginas de erro e no rodapé.
| Mudar aqui muda em todas as telas (o título das abas vem de config/adminlte.php).
|
*/

return [

    'nome' => 'Almoxarifado',

    'descricao' => 'Gestão de materiais e estoque',

    'orgao' => 'Laboratório DBA x DevOps',

    'unidade' => 'IFMT',

    // no Render, o commit publicado (o pipeline confere este valor depois de cada implantação)
    'versao' => env('APP_VERSION', substr((string) env('RENDER_GIT_COMMIT', 'local'), 0, 7)),

    // ícone do Font Awesome usado como marca (menu, login, páginas de erro)
    'icone' => 'fas fa-boxes',

    // selo de ambiente no topo e no login; produção não mostra selo
    'ambientes' => [
        'local' => 'Local',
        'testing' => 'Testes',
        'staging' => 'Homologação',
    ],

    // conta de demonstração mostrada no login (o banco publicado só tem dados fictícios)
    'demo' => env('ALMOX_DEMO', false),

];

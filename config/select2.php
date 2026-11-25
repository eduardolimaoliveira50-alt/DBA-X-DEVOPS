<?php

use App\Models\Fornecedor;
use App\Models\Produto;

/*
 * Listas do <x-select2 model="..."> (mesmo padrão do SVO): POST select2/{model} chama Model::getSelectList().
 */
return [
    'models' => [
        'produto' => Produto::class,
        'fornecedor' => Fornecedor::class,
    ],
];

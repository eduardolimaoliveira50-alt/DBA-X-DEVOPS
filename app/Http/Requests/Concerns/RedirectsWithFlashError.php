<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Para ações de botão (excluir), sem campos na tela: o erro volta como aviso no topo, não como "confira os campos".
 */
trait RedirectsWithFlashError
{
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(back()->with('error', $validator->errors()->first()));
    }
}

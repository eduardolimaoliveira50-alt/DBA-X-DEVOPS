<?php

namespace App\Http\Requests;

use App\Models\Produto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // a rota já exige produtos.gerenciar
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'codigo' => strtoupper(trim((string) $this->input('codigo'))),
            'ativo' => $this->boolean('ativo'),
        ]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'max:30'],
            'nome' => ['required', 'string', 'max:150'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'unidade_medida' => ['required', Rule::in(array_keys(Produto::UNIDADES))],
            'estoque_minimo' => ['required', 'integer', 'min:0', 'max:1000000'],
            'ativo' => ['boolean'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'codigo' => 'código',
            'categoria_id' => 'categoria',
            'unidade_medida' => 'unidade de medida',
            'estoque_minimo' => 'estoque mínimo',
        ];
    }
}

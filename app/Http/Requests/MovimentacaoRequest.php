<?php

namespace App\Http\Requests;

use App\Models\Movimentacao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovimentacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // a rota já exige movimentacoes.registrar
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'tipo' => ['required', Rule::in(array_keys(Movimentacao::TIPOS))],
            'produto_id' => ['required', 'integer', Rule::exists('produtos', 'id')->where('ativo', true)->whereNull('deleted_at')],
            'quantidade' => ['required', 'integer', 'min:1', 'max:100000'],
            'fornecedor_id' => ['nullable', 'required_if:tipo,entrada', 'integer', Rule::exists('fornecedores', 'id')->whereNull('deleted_at')],
            'documento' => ['nullable', 'string', 'max:40'],
            'observacao' => ['nullable', 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['produto_id' => 'produto', 'fornecedor_id' => 'fornecedor', 'observacao' => 'observação'];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return ['fornecedor_id.required_if' => 'Informe o fornecedor da entrada.'];
    }
}

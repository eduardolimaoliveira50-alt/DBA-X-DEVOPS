<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FornecedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // a rota já exige fornecedores.gerenciar
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cnpj' => preg_replace('/\D/', '', (string) $this->input('cnpj')) ?: null,
            'telefone' => preg_replace('/\D/', '', (string) $this->input('telefone')) ?: null,
            'email' => strtolower(trim((string) $this->input('email'))) ?: null,
        ]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'razao_social' => ['required', 'string', 'max:150'],
            'cnpj' => ['nullable', 'cnpj'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefone' => ['nullable', 'string', 'min:10', 'max:11'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['razao_social' => 'razão social', 'cnpj' => 'CNPJ'];
    }
}

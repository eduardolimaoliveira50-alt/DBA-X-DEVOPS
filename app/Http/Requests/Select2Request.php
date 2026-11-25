<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Select2Request extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'model' => ['required', 'string', 'max:50', Rule::in(array_keys(config('select2.models')))],
            'search' => ['nullable', 'string', 'max:255'],
            'context' => ['nullable', 'array'],
            'context.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'model' => 'modelo',
            'search' => 'busca',
            'context' => 'contexto',
            'context.*' => 'contexto',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'model' => strtolower((string) $this->route('model')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function context(): array
    {
        $context = $this->validated('context');

        return is_array($context) ? $context : [];
    }
}

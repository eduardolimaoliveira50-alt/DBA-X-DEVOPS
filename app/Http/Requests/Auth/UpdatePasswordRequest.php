<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Troca da própria senha em "Meu perfil" (como o PasswordController do Breeze): exige a senha atual.
 */
class UpdatePasswordRequest extends FormRequest
{
    /** Erros num bag próprio, para não misturar com outros formulários da tela. */
    protected $errorBag = 'updatePassword';

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'different:current_password', Password::min(8)->letters()->numbers()],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'current_password.current_password' => 'A senha atual não confere.',
            'password.different' => 'A senha nova precisa ser diferente da atual.',
            'password.confirmed' => 'A confirmação não é igual à senha nova.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['current_password' => 'senha atual', 'password' => 'senha nova'];
    }
}

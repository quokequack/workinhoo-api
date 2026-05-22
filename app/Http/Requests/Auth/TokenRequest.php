<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'size:8', 'regex:/^[a-f0-9]{8}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'O token é obrigatório.',
            'codigo.string' => 'O token não é válido.',
            'codigo.size' => 'O token deve ter 8 caracteres.',
            'codigo.regex' => 'O token informado é inválido.',
        ];
    }
}

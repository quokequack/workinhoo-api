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
            'token' => ['required', 'string', 'size:8', 'regex:/^[a-f0-9]{8}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'O token é obrigatório.',
            'token.string' => 'O token não é válido.',
            'token.size' => 'O token deve ter 8 caracteres.',
            'token.regex' => 'O token informado é inválido.',
        ];
    }
}

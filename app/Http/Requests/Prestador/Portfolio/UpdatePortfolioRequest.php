<?php

namespace App\Http\Requests\Prestador\Portfolio;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descricao' => 'required|string',
            'midia' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => 'A descrição é obrigatória.',
            'midia.required' => 'A mídia é obrigatória.',
            'midia.file' => 'A mídia deve ser um arquivo.',
            'midia.mimes' => 'A mídia deve ser jpg, jpeg, png, webp ou mp4.',
            'midia.max' => 'A mídia não pode ter mais de 10MB.',
        ];
    }
}

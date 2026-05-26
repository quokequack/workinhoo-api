<?php

namespace App\Http\Requests\Orcamento;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RespostaOrcamentoRequest extends FormRequest
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
            'solicitacao_id' => 'required',
            'valor' => 'required|numeric|gt:0',
            'observacao_prestador' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'solicitacao_id.required' => 'O ID da solicitação é obrigatório!',
            'valor.required' => 'O valor é obrigatório!',
            'valor.numeric' => 'Valor inválido!',
            'valor.gt' => 'O valor deve ser maior que R$0,00!',
            'observacao_prestador.string' => 'Observação inválida!',
        ];
    }
}

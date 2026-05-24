<?php

namespace App\Http\Requests\Orcamento;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SolicitacaoOrcamentoRequest extends FormRequest
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
            'prestador_id' => 'required',
            'especialidade_prestador_id' => 'required',
            'descricao' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'prestador_id.required' => 'O prestador é obrigatório!',
            'especialidade_prestador_id.required' => 'A especialidade é obrigatória!',
            'descricao.required' => 'A descrição do trabalho é obrigatória!',
            'descricao.string' => 'Descrição inválida!'
        ];
    }
}

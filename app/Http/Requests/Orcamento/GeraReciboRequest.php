<?php

namespace App\Http\Requests\Orcamento;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GeraReciboRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // pega o model automaticamente pelo parãmetro na rota
        $acordo = $this->route('acordo');

        if (! $acordo || ! $this->user()?->can('podeGerarRecibo', $acordo)) {
            return false;
        }

        if (! $acordo->orcamento?->aceito) {
            throw new HttpResponseException(response()->json([
                'message' => 'O orçamento precisa estar aceito para gerar um recibo.'
            ], 422));
        }

        // 3. Estado do Acordo: Intervém e força o 422 se já estiver encerrado
        if ($acordo->finalizado) {
            throw new HttpResponseException(response()->json([
                'message' => 'Este acordo já foi finalizado.'
            ], 422));
        }
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
            'data_servico' => ['required', 'date'],
        ];
    }
}

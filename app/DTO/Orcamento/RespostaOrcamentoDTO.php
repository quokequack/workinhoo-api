<?php

namespace App\DTO\Orcamento;

use App\Http\Requests\Orcamento\RespostaOrcamentoRequest;

class RespostaOrcamentoDTO
{
    public function __construct(
        private float $valor,
        private ?string $obsevacao_prestador,
    ) {}

    public static function fromRequest(RespostaOrcamentoRequest $request): RespostaOrcamentoDTO
    {
        return new self(
            $request->validated('valor'),
            $request->input('obsevacao_prestador'),
        );
    }

    public function toArray(): array
    {
        return [
            'valor' => $this->valor,
            'obsevacao_prestador' => $this->obsevacao_prestador,
        ];
    }
}

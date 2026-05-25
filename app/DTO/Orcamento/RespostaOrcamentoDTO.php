<?php

namespace App\DTO\Orcamento;

use App\Http\Requests\Orcamento\RespostaOrcamentoRequest;

class RespostaOrcamentoDTO
{
    public function __construct(
        public int $solicitacao_id,
        private float $valor,
        private ?string $observacao_prestador,
    ) {}

    public static function fromRequest(RespostaOrcamentoRequest $request): RespostaOrcamentoDTO
    {
        return new self(
            $request->validated('solicitacao_id'),
            $request->validated('valor'),
            $request->input('observacao_prestador'),
        );
    }

    public function toArray(): array
    {
        return [
            'valor' => $this->valor,
            'observacao_prestador' => $this->observacao_prestador,
        ];
    }
}

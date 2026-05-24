<?php

namespace App\DTO\Orcamento;

use App\Http\Requests\Orcamento\SolicitacaoOrcamentoRequest;

class SolicitacaoOrcamentoDTO
{
    public function __construct(
        private int $solicitante_id,
        private int $prestador_id,
        private int $especialidade_id,
        private string $descricao,
        private ?float $valor,
        private ?string $observacao_prestador,
        private ?bool $aceito
    ){}

    public static function fromRequest(SolicitacaoOrcamentoRequest $request): SolicitacaoOrcamentoDTO
    {
        return new self(
            3,
          $request->validated('prestador_id'),
          $request->validated('especialidade_prestador_id'),
          $request->validated('descricao'),
          null,
          null,
          null
        );
    }

    public function toArray(): array
    {
        return [
            'solicitante_id' => $this->solicitante_id,
            'prestador_id' => $this->prestador_id,
            'especialidade_prestador_id' => $this->especialidade_id,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'observacao_prestador' => $this->observacao_prestador,
            'aceito' => $this->aceito,
        ];
    }
}

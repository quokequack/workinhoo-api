<?php

namespace App\Actions\Orcamento;

use App\DTO\Orcamento\SolicitacaoOrcamentoDTO;
use App\Models\Orcamento\PrestadorOrcamento;

class CriaSolicitacao
{
    public function executa(SolicitacaoOrcamentoDTO $solicitacaoOrcamentoDTO): PrestadorOrcamento
    {
        return PrestadorOrcamento::query()->create($solicitacaoOrcamentoDTO->toArray());
    }
}

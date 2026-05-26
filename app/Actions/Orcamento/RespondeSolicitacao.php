<?php

namespace App\Actions\Orcamento;

use App\DTO\Orcamento\RespostaOrcamentoDTO;
use App\Exceptions\SolicitacaoNaoEncontradaException;
use App\Models\Orcamento\PrestadorOrcamento;

class RespondeSolicitacao
{
    public function executa(RespostaOrcamentoDTO $respostaOrcamentoDTO)
    {
        $solicitacao = PrestadorOrcamento::porId($respostaOrcamentoDTO->solicitacao_id);
        if (! $solicitacao) {
            throw SolicitacaoNaoEncontradaException::exception();
        }
        $solicitacao->update($respostaOrcamentoDTO->toArray());

        return $solicitacao;
    }
}

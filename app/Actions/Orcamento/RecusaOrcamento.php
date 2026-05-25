<?php

namespace App\Actions\Orcamento;

use App\Exceptions\SolicitacaoNaoEncontradaException;
use App\Models\Orcamento\PrestadorOrcamento;

class RecusaOrcamento
{
    public function executa(PrestadorOrcamento $solicitacao) : PrestadorOrcamento
    {
        $solicitacao->update([
            'aceito' => false,
        ]);
        return $solicitacao;
    }
}

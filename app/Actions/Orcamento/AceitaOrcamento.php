<?php

namespace App\Actions\Orcamento;

use App\Models\Orcamento\PrestadorOrcamento;

class AceitaOrcamento
{
    public function executa(PrestadorOrcamento $solicitacao): PrestadorOrcamento
    {
        $solicitacao->update([
            'aceito' => true,
        ]);

        return $solicitacao;
    }
}

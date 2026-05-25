<?php

namespace App\Actions\Orcamento;

use App\Models\Orcamento\Acordo;
use App\Models\Orcamento\PrestadorOrcamento;

class CriaAcordo
{
    public function executa(PrestadorOrcamento $solicitacao) : Acordo
    {
        return Acordo::query()->create([
            'orcamento_id' => $solicitacao->id,
            'finalizado' => false
        ]);
    }
}

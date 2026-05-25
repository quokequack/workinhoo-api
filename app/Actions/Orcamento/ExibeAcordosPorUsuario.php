<?php

namespace App\Actions\Orcamento;

use App\Models\Orcamento\PrestadorOrcamento;
use App\Models\Usuario\Usuario;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExibeAcordosPorUsuario
{
    public function executa(int $idUsuario) : Collection
    {
        return PrestadorOrcamento::query()
            ->select(
                'a.id',
                'prestador_orcamento.prestador_id',
                'prestador_orcamento.especialidade_prestador_id',
                'prestador_orcamento.descricao',
                'prestador_orcamento.valor',
                'a.created_at',
                'a.finalizado')
            ->join('acordos a', 'acordos.orcamento_id', '=', 'prestador_orcamento.id')
            ->where('prestador_orcamento.solicitante_id', '=', $idUsuario)
            ->groupBy('a.id',
                'prestador_orcamento.prestador_id',
                'prestador_orcamento.especialidade_prestador_id',
                'prestador_orcamento.descricao',
                'prestador_orcamento.valor',
                'a.created_at',
                'a.finalizado')
            ->get();
    }
}

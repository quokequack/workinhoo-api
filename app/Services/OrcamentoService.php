<?php

namespace App\Services;

use App\Actions\Orcamento\AceitaOrcamento;
use App\Actions\Orcamento\CriaAcordo;
use App\Actions\Orcamento\RecusaOrcamento;
use App\Exceptions\SolicitacaoNaoEncontradaException;
use App\Models\Orcamento\Acordo;
use App\Models\Orcamento\PrestadorOrcamento;
use Illuminate\Support\Facades\DB;

class OrcamentoService
{
    public function __construct(
        private readonly CriaAcordo $criaAcordoAction,
        private readonly AceitaOrcamento $aceitaOrcamentoAction,
        private readonly RecusaOrcamento $recusaOrcamentoAction,
    ) {}

    public function aceitaOrcamento(int $idSolicitacao): Acordo
    {
        $solicitacao = PrestadorOrcamento::porId($idSolicitacao);

        if (! $solicitacao) {
            throw SolicitacaoNaoEncontradaException::exception();
        }

        return DB::transaction(function () use ($solicitacao) {
            $this->aceitaOrcamentoAction->executa($solicitacao);

            return $this->criaAcordoAction->executa($solicitacao);
        });
    }

    public function recusaOrcamento(int $idSolicitacao): PrestadorOrcamento
    {
        $solicitacao = PrestadorOrcamento::porId($idSolicitacao);
        if (! $solicitacao) {
            throw SolicitacaoNaoEncontradaException::exception();
        }

        return $this->recusaOrcamentoAction->executa($solicitacao);
    }
}

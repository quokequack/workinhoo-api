<?php

namespace App\Http\Controllers\Orcamento;

use App\Actions\Orcamento\CriaSolicitacao;
use App\DTO\Orcamento\SolicitacaoOrcamentoDTO;
use App\Events\NovaSolicitacaoOrcamentoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orcamento\SolicitacaoOrcamentoRequest;
use App\Models\Usuario\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SolicitacaoOrcamentoController extends Controller
{
    public function __construct(private readonly CriaSolicitacao $criaSolicitacao){}

    public function novaSolicitacao(SolicitacaoOrcamentoRequest $request) : JsonResponse
    {
        try{
            $solicitacao = $this->criaSolicitacao->executa(SolicitacaoOrcamentoDTO::fromRequest($request));
            $this->enviaEmail($solicitacao->prestador->usuario);
            return $this->sucesso('Solicitação enviada com sucesso!');
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function enviaEmail(Usuario $prestador)
    {
        NovaSolicitacaoOrcamentoEvent::dispatch($prestador->email, $prestador->nome);
    }
}

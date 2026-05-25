<?php

namespace App\Http\Controllers\Orcamento;

use App\Actions\Orcamento\CriaSolicitacao;
use App\DTO\Orcamento\SolicitacaoOrcamentoDTO;
use App\Events\NovaSolicitacaoOrcamentoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orcamento\SolicitacaoOrcamentoRequest;
use App\Models\Usuario\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class SolicitacaoOrcamentoController extends Controller
{
    public function __construct(private readonly CriaSolicitacao $criaSolicitacao) {}

    public function novaSolicitacao(SolicitacaoOrcamentoRequest $request): JsonResponse
    {
        try {
            $solicitacao = $this->criaSolicitacao->executa(SolicitacaoOrcamentoDTO::fromRequest($request));
            $usuario = $solicitacao->usuario;

            if (! $usuario instanceof Usuario) {
                throw new RuntimeException('Prestador sem usuario vinculado.');
            }

            $this->enviaEmail($usuario);

            return $this->sucesso('Solicitação enviada com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function enviaEmail(Usuario $prestador): JsonResponse|array
    {
        try {
            return NovaSolicitacaoOrcamentoEvent::dispatch($prestador->email, $prestador->nome);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json('Erro ao enviar email!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

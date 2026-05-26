<?php

namespace App\Http\Controllers\Orcamento;

use App\Actions\Orcamento\CriaSolicitacao;
use App\Actions\Orcamento\ExibeAcordosPorUsuario;
use App\Actions\Orcamento\RespondeSolicitacao;
use App\DTO\Orcamento\RespostaOrcamentoDTO;
use App\DTO\Orcamento\SolicitacaoOrcamentoDTO;
use App\Events\Orcamento\NovaSolicitacaoOrcamentoEvent;
use App\Events\Orcamento\RespostaSolicitacaoOrcamentoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orcamento\RespostaOrcamentoRequest;
use App\Http\Requests\Orcamento\SolicitacaoOrcamentoRequest;
use App\Models\Orcamento\PrestadorOrcamento;
use App\Models\Usuario\Usuario;
use App\Services\OrcamentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class SolicitacaoOrcamentoController extends Controller
{
    public function __construct(private readonly CriaSolicitacao $criaSolicitacao,
        private readonly RespondeSolicitacao $respondeSolicitacao,
        private readonly OrcamentoService $service,
        private readonly ExibeAcordosPorUsuario $exibeAcordosPorUsuario, ) {}

    public function novaSolicitacao(SolicitacaoOrcamentoRequest $request): JsonResponse
    {
        try {
            $solicitacao = $this->criaSolicitacao->executa(SolicitacaoOrcamentoDTO::fromRequest($request));
            $this->enviaEmailPrestador($solicitacao);

            return $this->sucesso('Solicitação enviada com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function enviaEmailPrestador(PrestadorOrcamento $solicitacao): JsonResponse|array
    {
        try {
            $usuarioPrestador = $solicitacao->usuarioPrestador;

            if (! $usuarioPrestador instanceof Usuario) {
                throw new RuntimeException('Prestador sem usuario vinculado.');
            }

            return NovaSolicitacaoOrcamentoEvent::dispatch($usuarioPrestador->email, $usuarioPrestador->nome);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json('Erro ao enviar email!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function novaResposta(RespostaOrcamentoRequest $request): JsonResponse
    {
        try {
            $solicitacao = $this->respondeSolicitacao->executa(RespostaOrcamentoDTO::fromRequest($request));
            $this->enviaEmailSolicitante($solicitacao);

            return $this->sucesso('Resposta enviada com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function enviaEmailSolicitante(PrestadorOrcamento $solicitacao): JsonResponse|array
    {
        try {
            $solicitante = $solicitacao->solicitante;
            $prestador = $solicitacao->usuarioPrestador;

            if (! $solicitante instanceof Usuario || ! $prestador instanceof Usuario) {
                throw new RuntimeException('Prestador ou Solicitante sem usuario vinculado.');
            }

            return RespostaSolicitacaoOrcamentoEvent::dispatch($solicitante->email, $solicitante->nome, $prestador->nome);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json('Erro ao enviar email!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function aceitaOrcamento(int $idSolicitacao): JsonResponse
    {
        try {
            $this->service->aceitaOrcamento($idSolicitacao);

            return $this->sucesso('Acordo criado!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function recusaOrcamento(int $idSolicitacao): JsonResponse
    {
        try {
            $this->service->recusaOrcamento($idSolicitacao);

            return $this->sucesso('Orçamento recusado com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function acordosPorUsuario(int $idUsuario): JsonResponse
    {
        try {
            $this->exibeAcordosPorUsuario->executa($idUsuario);

            return $this->sucesso('Orçamento recusado com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

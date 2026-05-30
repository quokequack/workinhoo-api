<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ValidaEmailVerificado;
use App\Actions\Auth\VerificaEmail;
use App\Events\VerificarEmailEvent;
use App\Exceptions\UsuarioNaoEncontradoException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\TokenRequest;
use App\Models\Usuario\EmailVerificationToken;
use App\Services\Auth\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class VerificacaoEmailController extends Controller
{
    public function __construct(
        private readonly TokenService $tokenService,
        public EmailVerificationToken $model,
        public ValidaEmailVerificado $emailVerificado,
        private readonly VerificaEmail $verificaEmail) {}

    public function salvaCodigo(EmailRequest $request): ?JsonResponse
    {
        $email = $request->validated('email');

        if ($this->emailVerificado->executa($email)) {
            return $this->semConteudo('Email já verificado.');
        }

        $response = $this->tokenService->salvaToken($this->model, $request->input('email'));

        if (! $response) {
            throw UsuarioNaoEncontradoException::exception();
        }

        return $this->enviaEmail($response);
    }

    public function validaCodigo(TokenRequest $request): JsonResponse
    {
        $codigoInformado = $request->validated('codigo');

        try {
            $this->tokenService->validaTokens($this->model, $codigoInformado);
            $this->verificaEmail->executa((string) session('email_recuperacao'));

            return $this->sucesso(['message' => 'Email verificado!']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->erro($e->getMessage());
        }
    }

    private function enviaEmail($response): JsonResponse
    {
        try {
            if ($response) {
                VerificarEmailEvent::dispatch(
                    $response['email'],
                    $response['nome'],
                    $response['codigo'],
                );
            }

            return $this->sucesso(['message' => 'Email enviado']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->erro($e->getMessage());
        }
    }
}

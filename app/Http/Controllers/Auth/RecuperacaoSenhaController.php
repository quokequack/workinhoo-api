<?php

namespace App\Http\Controllers\Auth;

use App\Events\RecuperarSenhaEvent;
use App\Exceptions\UsuarioNaoEncontradoException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\TokenRequest;
use App\Models\Usuario\PasswordResetTokens;
use App\Services\Auth\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RecuperacaoSenhaController extends Controller
{
    public function __construct(private readonly TokenService $tokenService, private readonly PasswordResetTokens $model) {}

    public function enviaCodigo(EmailRequest $request): JsonResponse
    {
        $email = $request->validated('email');

        $response = $this->tokenService->salvaToken($this->model, $email);

        if (is_null($response)) {
            throw UsuarioNaoEncontradoException::exception();
        }

        try {
            RecuperarSenhaEvent::dispatch(
                $response['email'],
                $response['nome'],
                $response['codigo']
            );

            return $this->sucesso(['message' => 'Email enviado']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->erro($e->getMessage());
        }

    }

    public function validaCodigo(TokenRequest $request): JsonResponse
    {
        $codigoInformado = $request->validated('codigo');

        try {
            $this->tokenService->validaTokens($this->model, $codigoInformado);
            return $this->sucesso(['message' => 'Código válido']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->erro($e->getMessage());
        }
    }
}

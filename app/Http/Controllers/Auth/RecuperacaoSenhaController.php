<?php

namespace App\Http\Controllers\Auth;

use App\Events\RecuperarSenhaEvent;
use App\Http\Controllers\Controller;
use App\Models\Usuario\PasswordResetTokens;
use App\Services\Auth\RecuperarSenha\RecuperacaoSenhaTokenService;
use App\Services\Auth\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RecuperacaoSenhaController extends Controller
{
    public function __construct(private readonly TokenService $tokenService, public PasswordResetTokens $model) {}

    public function enviaCodigo(Request $request)
    {
        $email = $request->input('email');

        if (!$email) {
            return response()->json('Email não informado!', Response::HTTP_BAD_REQUEST);
        }

        $response = $this->tokenService->salvaToken($this->model, $email);

        if (is_null($response)) {
            return response()->json('Email não cadastrado!', Response::HTTP_NOT_FOUND);
        }

        try{
            RecuperarSenhaEvent::dispatch(
                $response['email'],
                $response['nome'],
                $response['codigo']
            );
            return $this->sucesso(['message' => 'Email enviado']);
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function validaCodigo(Request $request)
    {
        $codigoInformado = $request->input('codigo');

        if (!$codigoInformado) {
            return response()->json('Nenhum token foi informado!', Response::HTTP_BAD_REQUEST);
        }

        try{
            $this->tokenService->validaTokens($this->model, $codigoInformado);
            return $this->sucesso(['message' => 'Código válido']);
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

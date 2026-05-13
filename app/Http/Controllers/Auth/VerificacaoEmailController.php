<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ValidaEmailVerificado;
use App\Actions\Auth\VerificaEmail;
use App\Events\VerificarEmailEvent;
use App\Http\Controllers\Controller;
use App\Models\Usuario\EmailVerificationToken;
use App\Services\Auth\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerificacaoEmailController extends Controller
{
    public function __construct(
        private readonly TokenService $tokenService,
        public EmailVerificationToken $model,
        public ValidaEmailVerificado  $emailVerificado,
        private readonly VerificaEmail $verificaEmail) {}

    public function salvaCodigo(Request $request)
    {
        $email = $request->input('email');

        if(!$email) {
            return response()->json(['Email não informado!', Response::HTTP_BAD_REQUEST]);
        }

        if($this->emailVerificado->executa($email)) {
            return response()->json(['Email já verificado!', Response::HTTP_NO_CONTENT]);
        }

        $response = $this->tokenService->salvaToken($this->model, $request->input('email'));

        if(!$response) {
            return response()->json(['Email não cadastrado!', Response::HTTP_NOT_FOUND]);
        }
        $this->enviaEmail($response);
    }

    private function enviaEmail($response)
    {
        try{
            if ($response) {
                VerificarEmailEvent::dispatch(
                    $response['email'],
                    $response['nome'],
                    $response['codigo'],
                );
            }

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
            $this->verificaEmail->executa($codigoInformado->email);
            return $this->sucesso(['message' => 'Email verificado!']);
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}

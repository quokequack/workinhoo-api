<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AlterarSenhaRequest;
use App\Services\Auth\AlterarSenhaService;
use Illuminate\Support\Facades\Log;

class AlterarSenhaController extends Controller
{
    public function __construct(private readonly AlterarSenhaService $alterarSenhaService) {}

    public function alterarSenha(AlterarSenhaRequest $request)
    {
        try {
            $this->alterarSenhaService->alterarSenha($request->validated('senha'));

            return $this->sucesso('Senha alterada com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->erro($e->getMessage());
        }
    }
}

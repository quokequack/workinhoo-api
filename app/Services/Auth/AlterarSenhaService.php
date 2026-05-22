<?php

namespace App\Services\Auth;

use App\Actions\Auth\AlteraSenha;
use App\Exceptions\TokenInvalidoException;
use App\Exceptions\UsuarioNaoEncontradoException;
use App\Models\Usuario\Usuario;
use Illuminate\Http\JsonResponse;

class AlterarSenhaService
{

    public function __construct(private readonly AlteraSenha $alteraSenha){}

    public function alterarSenha(string $senha) :JsonResponse|Usuario
    {
        $usuario = $this->recuperaUsuario();
        if(!$usuario){
            throw UsuarioNaoEncontradoException::exception();
        }

        return $this->alteraSenha->executa($usuario, $senha);
    }
    private function recuperaUsuario(): ?Usuario
    {
        if(!session()->has('email_recuperacao')){
            throw TokenInvalidoException::exception();
        }

        return Usuario::porEmail(session('email_recuperacao'));

    }
}

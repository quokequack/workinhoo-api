<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsuarioNaoEncontradoException extends Exception
{
    public static function exception(): Exception
    {
        return new NotFoundHttpException(
            'Usuário não encontrado!',
        );
    }
}

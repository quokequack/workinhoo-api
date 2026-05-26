<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\ItemNotFoundException;

class UsuarioNaoEncontradoException extends Exception
{
    public static function exception(): Exception
    {
        return new ItemNotFoundException(
            'Usuário não encontrado!',
        );
    }
}

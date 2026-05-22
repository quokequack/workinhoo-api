<?php

namespace App\Exceptions;

use Illuminate\Support\ItemNotFoundException;
use Illuminate\Validation\ValidationException;

class UsuarioNaoEncontradoException extends \Exception
{
    public static function exception(): ItemNotFoundException
    {
        return ItemNotFoundException::withMessages([
            'usuario' => 'Usuário não encontrado!',
        ]);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\ItemNotFoundException;

class SolicitacaoNaoEncontradaException extends Exception
{
    public static function exception(): Exception
    {
        return new ItemNotFoundException('Solicitação não encontrada!');
    }
}

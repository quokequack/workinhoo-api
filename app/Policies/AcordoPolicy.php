<?php

namespace App\Policies;

use App\Models\Orcamento\Acordo;
use App\Models\Usuario\Usuario;

class AcordoPolicy
{
    /**
     * Create a new policy instance.
     */
    public function podeGerarRecibo(Usuario $usuario, Acordo $acordo): bool
    {
        $orcamento = $acordo->orcamento;

        if (! $orcamento) {
            return false;
        }

        $isPrestador = $usuario->id === $orcamento->usuarioPrestador?->id;
        $isSolicitante = $usuario->id === $orcamento->solicitante_id;

        return $isPrestador || $isSolicitante;
    }
}

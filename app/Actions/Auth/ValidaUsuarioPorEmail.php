<?php

namespace App\Actions\Auth;

use App\Models\Usuario\Usuario;

class ValidaUsuarioPorEmail
{
    public function executa(string $email): ?Usuario
    {
        $usuario = Usuario::porEmail($email);

        if (! $usuario) {
            return null;
        }

        return $usuario;
    }
}

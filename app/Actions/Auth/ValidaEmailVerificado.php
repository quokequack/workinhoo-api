<?php

namespace App\Actions\Auth;

use App\Models\Usuario\Usuario;

class ValidaEmailVerificado
{
    public function executa(string $email): bool
    {
        $usuario = Usuario::porEmail($email);

        if (! $usuario) {
            return false;
        }

        return $usuario->email_verified_at != null;
    }
}

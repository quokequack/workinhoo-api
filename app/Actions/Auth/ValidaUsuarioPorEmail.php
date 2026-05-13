<?php

namespace App\Actions\Auth;

use App\Models\Usuario\Usuario;
use Illuminate\Database\Eloquent\Model;

class ValidaUsuarioPorEmail
{
    public function executa($email) : ?Usuario
    {
        $usuario = Usuario::porEmail($email);

        if(!$usuario){
            return null;
        }

        return $usuario;
    }

}

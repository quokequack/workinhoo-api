<?php

namespace App\Actions\Auth;

use App\Models\Usuario\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VerificaEmail
{
    public function executa(string $email)
    {
        $usuario = Usuario::porEmail($email);

        if ($usuario->email_verified_at === null) {
            $usuario->forceFill(['email_verified_at' => Carbon::now()->format('d-m-Y H:i:s')])->save();
        }

        return $usuario;
    }
}

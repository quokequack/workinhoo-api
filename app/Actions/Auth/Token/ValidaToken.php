<?php

namespace App\Actions\Auth\Token;

use App\Models\Usuario\EmailVerificationToken;
use App\Models\Usuario\PasswordResetTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ValidaToken
{
    public function executa(PasswordResetTokens|EmailVerificationToken $model, string $token): ?Model
    {
        $tokenExistente = $model->porToken($token);

        if (! $tokenExistente) {
            return null;
        }

        $expiracaoEmMinutos = config('auth.token_ttl');

        Carbon::parse($tokenExistente->created_at)
            ->addMinutes($expiracaoEmMinutos)
            ->isFuture();

        return $tokenExistente;

    }
}

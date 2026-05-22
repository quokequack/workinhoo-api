<?php

namespace App\Actions\Auth\Token;

use App\Models\Usuario\EmailVerificationToken;
use App\Models\Usuario\PasswordResetTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ValidaToken
{
    public function executa(PasswordResetTokens|EmailVerificationToken $model, string $token): ?bool
    {

        $tokenExistente = $this->buscarTokenValido($model);
        if(!$tokenExistente){
           return false;
        }

        if(!Hash::check($token, $tokenExistente->token)){
            return false;
        }

        $expiracaoEmMinutos = (int) config('auth.token_ttl')['expire'];

        $criadoEm = Carbon::parse($tokenExistente->created_at);

        return $criadoEm->copy()
            ->addMinutes($expiracaoEmMinutos)
            ->isFuture();

    }

    private function buscarTokenValido(PasswordResetTokens|EmailVerificationToken $model) : ?Model
    {

        if(!session()->has('email_recuperacao') || !session()->has('expires_at')){
            return null;
        }

        $expires_at = Carbon::parse(session('expires_at'));

        if (now()->gt($expires_at)) {
            session()->forget(['email_recuperacao', 'expires_at']);
            return null;
        }

       return $model->porEmail(session('email_recuperacao'));

    }
}

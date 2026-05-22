<?php

namespace App\Services\Auth;

use App\Actions\Auth\Token\SalvaToken;
use App\Actions\Auth\Token\ValidaToken;
use App\Actions\Auth\ValidaUsuarioPorEmail;
use App\Exceptions\TokenInvalidoException;
use App\Models\Usuario\EmailVerificationToken;
use App\Models\Usuario\PasswordResetTokens;
use App\Models\Usuario\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TokenService
{
    public function __construct(private readonly SalvaToken $salvaTokenAction,
        private readonly ValidaToken $validaTokenAction,
        private readonly ValidaUsuarioPorEmail $validaUsuarioPorEmail) {}

    public function salvaToken(Model $model, string $email)
    {
        $usuario = $this->validaUsuarioPorEmail->executa($email);

        if (! $usuario) {
            return null;
        }

        $codigo = $this->gerarCodigoConfirmacao();
        $this->salvaTokenAction->executa($model, $usuario->email, $codigo);

        session([
            'email_recuperacao' => $usuario->email,
            'expires_at' => Carbon::now()->addMinutes(15)->toDateTimeString()
        ]);

        return ['email' => $usuario->email, 'nome' => $usuario->nome, 'codigo' => $codigo];
    }

    public function validaTokens(PasswordResetTokens|EmailVerificationToken $model, string $token): ?string
    {
        $ehValido = $this->validaTokenAction->executa($model, $token);

        if (!$ehValido) {
            throw TokenInvalidoException::exception();
        }

        return $ehValido;

    }

    private function gerarCodigoConfirmacao(): string
    {
        return bin2hex(random_bytes(4));
    }
}

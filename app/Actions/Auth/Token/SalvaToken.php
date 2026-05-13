<?php

namespace App\Actions\Auth\Token;

use App\Models\Usuario\PasswordResetTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SalvaToken
{
    public function executa(Model $model, string $email, string $token): void
    {
       $model->query()->updateOrCreate(
           ['email' => $email],
           [
               'token' => $token,
               'created_at' => Carbon::now()->format('d-m-Y H:i:s'),
           ],
        );
    }
}

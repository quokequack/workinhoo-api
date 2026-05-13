<?php

namespace App\Listeners;

use App\Events\RecuperarSenhaEvent;
use App\Mail\RecuperarSenhaMailable;
use Illuminate\Support\Facades\Mail;

class RecuperarSenhaListener
{
    public function handle(RecuperarSenhaEvent $evento): void
    {
        $email = new RecuperarSenhaMailable($evento->email, $evento->nome, $evento->codigo);
        Mail::to($evento->email)->queue($email);
    }
}

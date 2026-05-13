<?php

namespace App\Listeners;

use App\Events\VerificarEmailEvent;
use App\Mail\VerificarEmailMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class VerificarEmailListener
{
    public function handle(VerificarEmailEvent $evento): void
    {
        $email = new VerificarEmailMailable($evento->email, $evento->nome, $evento->codigo);
        Mail::to($evento->email)->queue($email);
    }
}

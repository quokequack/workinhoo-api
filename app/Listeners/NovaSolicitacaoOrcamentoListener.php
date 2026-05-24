<?php

namespace App\Listeners;

use App\Events\NovaSolicitacaoOrcamentoEvent;
use App\Mail\NovaSolicitacaoOrcamentoMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NovaSolicitacaoOrcamentoListener
{

    public function handle(NovaSolicitacaoOrcamentoEvent $evento): void
    {
        $email = new NovaSolicitacaoOrcamentoMailable($evento->email, $evento->nomeProvedor);
        Mail::to($email)->queue($email);
    }
}

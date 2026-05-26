<?php

namespace App\Listeners\Orcamento;

use App\Events\Orcamento\NovaSolicitacaoOrcamentoEvent;
use App\Mail\Orcamento\NovaSolicitacaoOrcamentoMailable;
use Illuminate\Support\Facades\Mail;

class NovaSolicitacaoOrcamentoListener
{
    public function handle(NovaSolicitacaoOrcamentoEvent $evento): void
    {
        $email = new NovaSolicitacaoOrcamentoMailable($evento->email, $evento->nomeProvedor);
        Mail::to($evento->email)->queue($email);
    }
}

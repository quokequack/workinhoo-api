<?php

namespace App\Listeners\Orcamento;

use App\Events\Orcamento\RespostaSolicitacaoOrcamentoEvent;
use App\Mail\Orcamento\RespostaSolicitacaoOrcamentoMailable;
use Illuminate\Support\Facades\Mail;

class RespostaSolicitacaoOrcamentoListener
{
    public function handle(RespostaSolicitacaoOrcamentoEvent $evento): void
    {
        $email = new RespostaSolicitacaoOrcamentoMailable($evento->email, $evento->nome, $evento->prestador);
        Mail::to($evento->email)->queue($email);
    }
}

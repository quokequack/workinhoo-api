<?php

namespace App\Providers;

use App\Events\Orcamento\NovaSolicitacaoOrcamentoEvent;
use App\Events\RecuperarSenhaEvent;
use App\Events\VerificarEmailEvent;
use App\Listeners\Orcamento\NovaSolicitacaoOrcamentoListener;
use App\Listeners\RecuperarSenhaListener;
use App\Listeners\VerificarEmailListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(RecuperarSenhaEvent::class, RecuperarSenhaListener::class);
        Event::listen(VerificarEmailEvent::class, VerificarEmailListener::class);
        Event::listen(NovaSolicitacaoOrcamentoEvent::class, NovaSolicitacaoOrcamentoListener::class);
    }
}

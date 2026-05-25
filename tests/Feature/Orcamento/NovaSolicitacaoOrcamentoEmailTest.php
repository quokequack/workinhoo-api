<?php

use App\Events\NovaSolicitacaoOrcamentoEvent;
use App\Mail\NovaSolicitacaoOrcamentoMailable;
use Illuminate\Support\Facades\Mail;

test('evento de nova solicitacao de orcamento enfileira email para o prestador', function () {
    Mail::fake();

    NovaSolicitacaoOrcamentoEvent::dispatch('prestador@example.com', 'Maria');

    Mail::assertQueued(
        NovaSolicitacaoOrcamentoMailable::class,
        fn (NovaSolicitacaoOrcamentoMailable $email) => $email->hasTo('prestador@example.com')
            && $email->nome === 'Maria'
    );
});

test('email de nova solicitacao de orcamento renderiza conteudo', function () {
    $html = (new NovaSolicitacaoOrcamentoMailable('prestador@example.com', 'Maria'))->render();

    expect($html)
        ->toContain('Olá, <strong>Maria</strong>!')
        ->toContain('Você tem uma nova solicitação de orçamento no Workinhoo!');
});

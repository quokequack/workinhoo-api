<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NovaSolicitacaoOrcamentoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly string $email,
                                public readonly string $nome) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nova solicitação!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nova_solicitacao_orcamento',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail\Orcamento;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RespostaSolicitacaoOrcamentoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly string $email,
        public readonly string $nome,
        public readonly string $prestador) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->prestador} te enviou um orçamento!",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nova_resposta_orcamento',
            with: [
                'nome' => $this->nome,
                'provedor' => $this->prestador,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

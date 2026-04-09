<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BackInStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public $variante;

    public function __construct($variante)
    {
        $this->variante = $variante;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $nombre = $this->variante->producto->nombre ?? 'Tu producto favorito';
        return new Envelope(
            subject: '¡Buenas noticias! ' . $nombre . ' vuelve a estar disponible',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.back_in_stock',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

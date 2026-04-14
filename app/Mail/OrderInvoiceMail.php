<?php

namespace App\Mail;

use App\Models\Orden;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * @property \App\Models\Orden $venta
 */
class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Orden $venta;

    /**
     * Create a new message instance.
     */
    public function __construct(Orden $venta)
    {
        $this->venta = $venta;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Factura Electrónica de Stitch & Co (#'.str_pad($this->venta->id, 5, '0', STR_PAD_LEFT).')',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.factura',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

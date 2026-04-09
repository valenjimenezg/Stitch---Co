<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FacturaAprobadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $venta;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct($venta, $pdfContent)
    {
        $this->venta = $venta;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu pago ha sido aprobado! Factura de tu Orden #STR-' . str_pad($this->venta->id, 6, '0', STR_PAD_LEFT),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.factura_aprobada',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'Factura-StitchCo-' . $this->venta->id . '.pdf')
                    ->withMime('application/pdf'),
        ];
    }
}

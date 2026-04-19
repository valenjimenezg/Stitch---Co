<?php

namespace App\Mail;

use App\Models\Orden;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orden;
    public $invoiceUrl;

    public function __construct(Orden $orden, $invoiceUrl)
    {
        $this->orden = $orden;
        $this->invoiceUrl = $invoiceUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $tipo = ($this->orden->monto_abonado > 0 && $this->orden->monto_abonado < $this->orden->total_amount) ? 'Ticket de Abono' : 'Factura Oficial';
        return new Envelope(
            subject: "Tu {$tipo} - Pedido #" . str_pad($this->orden->id, 5, '0', STR_PAD_LEFT) . " adelantada exitosamente",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

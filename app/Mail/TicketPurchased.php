<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class TicketPurchased extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct( public Ticket $ticket)
    {
        // Carregar relacionamentos necessários
        $this->ticket->load([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus'
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
           from: new Address('noreply@citylink.co.mz', 'CityLink e-Ticket'),
            subject: 'Seu Bilhete - ' . $this->ticket->ticket_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-purchased',
            with: [
                'ticket' => $this->ticket,
                'passenger' => $this->ticket->passenger,
                'schedule' => $this->ticket->schedule,
                'route' => $this->ticket->schedule->route,
            ],
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

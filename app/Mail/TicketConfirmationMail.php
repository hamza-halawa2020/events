<?php

namespace App\Mail;

use App\Models\Registration;
use App\Services\TicketService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Registration $registration;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration->loadMissing(['event.company', 'ticketType']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎟️ Your Ticket for ' . $this->registration->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-confirmation',
            with: [
                'registration' => $this->registration,
                'event' => $this->registration->event,
                'company' => $this->registration->event->company,
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = TicketService::generatePdf($this->registration);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Ticket-' . $this->registration->ticket_code . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

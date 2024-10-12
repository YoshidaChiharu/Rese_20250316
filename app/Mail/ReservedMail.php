<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;
    protected $qr_code;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation, $qr_code)
    {
        $this->reservation = $reservation;
        $this->qr_code = $qr_code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '<Rese> ご予約完了メール',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reserved_mail',
            with: [
                'reservation' => $this->reservation,
                'qr_code' => $this->qr_code,
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

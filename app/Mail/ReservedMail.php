<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * 「予約完了メール」送信用のMailクラス
 *
 * @var mixed $reservation 予約情報
 * @var mixed $url 予約QRコード表示ページのURL
 */
class ReservedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;
    protected $url;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation, $url)
    {
        $this->reservation = $reservation;
        $this->url = $url;
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
                'url' => $this->url,
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

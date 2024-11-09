<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * 「決済完了メール」送信用のMailクラス
 *
 * @var mixed $reservation 決済対象の予約情報
 * @var mixed $card_brand 決済に使用したカードのブランド名
 * @var mixed $card_last4 決済に使用したカード番号下4桁
 */
class PurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;
    protected $card_brand;
    protected $card_last4;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation, $card_brand, $card_last4)
    {
        $this->reservation = $reservation;
        $this->card_brand = $card_brand;
        $this->card_last4 = $card_last4;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '<Rese> 事前決済完了メール',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.purchased_mail',
            with: [
                'reservation' => $this->reservation,
                'card_brand' => $this->card_brand,
                'card_last4' => $this->card_last4,
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

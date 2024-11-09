<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * 管理者から利用者へ「お知らせメール」送信用のMailクラス
 *
 * @var mixed $subject メール題名
 * @var mixed $main_text メール本文
 * @var mixed $name メール送信対象ユーザーの名前
 */
class AdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    protected $main_text;
    protected $name;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $subject, $main_text)
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->main_text = $main_text;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_mail',
            with: [
                'main_text' => $this->main_text,
                'name' => $this->name,
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

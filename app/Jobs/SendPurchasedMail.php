<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchasedMail;

class SendPurchasedMail implements ShouldQueue
{
    use Queueable, Dispatchable;

    private $reservation;
    private $card_brand;
    private $card_last4;

    /**
     * Create a new job instance.
     */
    public function __construct($reservation, $card_brand, $card_last4)
    {
        $this->reservation = $reservation;
        $this->card_brand = $card_brand;
        $this->card_last4 = $card_last4;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->reservation->user;
        Log::info('事前決済完了メール送信:'.$user->email);
        Mail::to($user->email)->send(new PurchasedMail($this->reservation, $this->card_brand, $this->card_last4));
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mail;
use App\Mail\ReservedMail;

class SendReservedMail implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable;

    private $reservation;
    private $url;

    /**
     * Create a new job instance.
     */
    public function __construct($reservation, $url)
    {
        $this->reservation = $reservation;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->reservation->user;
        Log::info('予約完了メール送信:'.$user->email);
        Mail::to($user->email)->send(new ReservedMail($this->reservation, $this->url));
    }
}

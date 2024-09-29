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
use App\Mail\ReminderMail;

class SendReminderMail implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable;

    private $reservation;
    private $user;

    /**
     * Create a new job instance.
     */
    public function __construct($reservation ,$user)
    {
        $this->reservation = $reservation;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('リマインダーメール送信:'.$this->user->email);
        Mail::to($this->user->email)->send(new ReminderMail($this->reservation, $this->user->name));
    }
}

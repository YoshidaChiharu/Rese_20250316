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
use App\Mail\AdminMail;

class SendAdminMail implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable;

    private $user;
    private $subject;
    private $main_text;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $subject, $main_text)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->main_text = $main_text;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('お知らせメール送信:'.$this->user->email);
        Mail::to($this->user->email)->send(new AdminMail(
            $this->user->name,
            $this->subject,
            $this->main_text,
        ));
    }
}

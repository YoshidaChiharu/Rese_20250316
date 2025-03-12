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

/**
 * 管理者から利用者へ「お知らせメール」送信用のJobクラス
 *
 * @var mixed $user メール送信対象ユーザー
 * @var mixed $subject メール題名
 * @var mixed $main_text メール本文
 */
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

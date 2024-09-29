<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Jobs\SendReminderMail;
use Illuminate\Support\Facades\Bus;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '当日予約分の予約情報をリマインダーメールとして送信';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 当日分の予約情報を取得
        $reservations = Reservation::where('scheduled_on', today()->format('Y-m-d'))->get();

        // 該当ユーザーへメール送信するジョブを作成
        $jobs = $reservations->map(function ($reservation) {
            return new SendReminderMail($reservation ,$reservation->user);
        });

        $batch = Bus::batch($jobs)->dispatch();
    }
}

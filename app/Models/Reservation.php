<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Shop;
use App\Models\Review;
use App\Models\Course;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * 予約したユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 予約した店舗を取得
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * 予約したコースの情報を取得
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * 予約時間の長さを分(min)で取得
     *
     * @return double 予約時間の長さ(min)
     */
    public function getMinutesLength()
    {
        $start = Carbon::create($this->start_at);
        $finish = Carbon::create($this->finish_at);

        return $start->diffInMinutes($finish);
    }
}

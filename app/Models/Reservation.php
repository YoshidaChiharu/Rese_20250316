<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shop;
use App\Models\Review;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
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

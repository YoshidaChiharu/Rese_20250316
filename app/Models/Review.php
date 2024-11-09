<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reservation;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * 口コミに紐づく予約情報を取得
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * 口コミ登録したユーザーを取得
     */
    public function reviewer()
    {
        return $this->reservation->user;
    }
}

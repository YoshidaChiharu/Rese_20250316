<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Course;
use Carbon\Carbon;


class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        $reservations = $this->reservations;
        foreach ($reservations as $reservation) {
            if ($reservation->review) {
                $reviews[] = $reservation->review;
            }
        }

        if (isset($reviews)) {
            $reviews = collect($reviews);
        } else {
            $reviews = collect(null);
        }

        return $reviews;
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // 店舗のレーティング値取得メソッド
    public function getShopRating()
    {
        $standard_value = 3;        // レーティングの基準値
        $weight = 20;               // 重み付け
        $total_review_rating = 0;   // 口コミ評価レーティングの合計値
        $count_num = 0;             // 口コミ人数

        $reviews = $this->reviews();
        if ($reviews) {
            foreach ($reviews as $review) {
                $total_review_rating += ($review->rating ?? 0);
                $count_num++;
            }
        }

        $shop_rating = (($standard_value * $weight) + $total_review_rating) / ($weight + $count_num);
        // 小数点第3位を四捨五入＆小数点以下2桁で揃える
        $shop_rating = round($shop_rating, 2);
        $shop_rating = number_format($shop_rating, 2);

        return $shop_rating;
    }

    // 店舗の口コミ人数取得メソッド
    public function getReviewsQuantity()
    {
        return $this->reviews()->count();
    }

    // 店舗のお気に入り登録人数取得メソッド
    public function getFavoritesQuantity()
    {
        return $this->favorites->count();
    }

    // 予約可能時間の配列取得メソッド
    public function getReservableTimes($start_time, $end_time, $span_minute) {
        $span_minute = 30;

        $time = new Carbon($start_time);
        $end = new Carbon($end_time);
        while ($time <= $end) {
            $reservable_times[] = $time->format('H:i');
            $time->addMinutes($span_minute);
        }

        return $reservable_times;
    }
}


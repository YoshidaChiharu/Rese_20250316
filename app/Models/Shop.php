<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Course;
use App\Models\Review;
use Carbon\Carbon;


class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * お気に入り登録している全てのユーザーを取得
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * お店に紐づいている全ての予約情報を取得
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * お店に紐づいている全てのお気に入り情報を取得
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * お店に紐づいている全ての口コミ情報を取得
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * お店に登録されている全てのコース情報を取得
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * 店舗のレーティング値を取得
     *
     * @var int $standard_value レーティングの基準値
     * @var int $weight 重み付け
     * @var int $total_review_rating 口コミ評価レーティングの合計値
     * @var int $count_num 口コミ人数
     * @return string 店舗のレーティング値
     */
    public function getShopRating()
    {
        $standard_value = 3;
        $weight = 20;
        $total_review_rating = 0;
        $count_num = 0;

        $reviews = $this->reviews;
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

    /**
     * 店舗の口コミ人数を取得
     *
     * @return int 口コミ人数
     */
    public function getReviewsQuantity()
    {
        return $this->reviews->count();
    }

    /**
     * 店舗のお気に入り登録人数を取得
     *
     * @return int お気に入り登録人数
     */
    public function getFavoritesQuantity()
    {
        return $this->favorites->count();
    }

    /**
     * 予約フォームで予約時間の選択肢として表示する為の配列を取得
     *
     * @param string $start_time 予約可能時間帯の開始時刻
     * @param string $end_time 予約可能時間帯の終了時刻
     * @param int $span_minute 時間間隔(min)
     * @return array 予約可能時間の配列
     */
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


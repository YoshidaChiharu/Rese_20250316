<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Reservation;
use App\Models\Review;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ReserveRequest;
use App\Http\Requests\ReviewRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopController extends Controller
{
    // 飲食店一覧ページ表示 ================================================
    public function index(Request $request) {
        $shops = Shop::all();

        // area,genreを抽出してそれぞれ配列作成
        $areas = $shops->pluck('area')->toArray();
        $areas = array_unique($areas);
        $genres = $shops->pluck('genre')->toArray();
        $genres = array_unique($genres);

        // 検索処理
        $input_area = $request->area;
        $input_genre = $request->genre;
        $input_name = $request->name;
        if ( !empty($input_area) ) {
            $shops = $shops->where('area', $input_area);
        }
        if ( !empty($input_genre) ) {
            $shops = $shops->where('genre', $input_genre);
        }
        if ( !empty($input_name) ) {
            $shops = $shops->filter(function ($shop) use ($input_name) {
                return preg_match("/{$input_name}/", $shop['name']);
            });
        }

        // 店舗一覧の検索条件をセッションに保存（shop_detail.blade.phpで使用）
        session(['previous_page' => $request->getRequestUri()]);

        // 店舗の「評価値」「レビュー数」「お気に入り数」を算出
        foreach ($shops as $shop) {
            $shop->rating = $shop->getShopRating();
            $shop->reviews_quantity = $shop->getReviewsQuantity();
            $shop->favorites_quantity = $shop->getFavoritesQuantity();
        }

        // お気に入り登録済み店舗の取得
        $favorite_shops = Auth::user()->favorite_shops;
        foreach ($shops as $shop) {
            $shop->favorite_flag = 0;
            foreach ($favorite_shops as $favorite_shop) {
                if ($shop->id === $favorite_shop->id) {
                    $shop->favorite_flag = 1;
                }
            }
        }

        return view(
            'shop_all',
            compact([
                'shops',
                'areas',
                'genres',
                'input_area',
                'input_genre',
                'input_name',
            ])
        );
    }

    // お気に入り更新処理 ==================================================
    public function updateFavorites(Request $request) {
        try {
            DB::transaction(function () use($request) {
                $user_id = Auth::user()->id;
                $shop_id = $request->shop_id;
                $flag = $request->favorite_flag;

                // お気に入り登録
                if ($flag == 0) {
                    Favorite::create([
                        'user_id' => $user_id,
                        'shop_id' => $shop_id,
                    ]);
                }

                // お気に入り削除
                if ($flag == 1) {
                    Favorite::where('user_id', $user_id)
                        ->where('shop_id', $shop_id)
                        ->delete();
                }
            });
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect(session('previous_page'));
    }

    // 飲食店詳細ページ表示 ================================================
    public function showShopDetail(Request $request) {
        $shop = Shop::find($request->shop_id);

        // 予約時間セレクトボックス用の選択肢作成
        $reservable_times = $this->getEqualIntervalTimes('16:00', '21:00', 30);

        // 予約可能最大人数
        $reserve_max_number = 10;

        // 口コミ情報の取得
        $reviews = $shop->reviews();
        foreach ($reviews as $review) {
            $review->review_date = ($review->updated_at)->format('Y-m-d');
            $review->visit_date = (new Carbon($review->reservation->scheduled_on))->format('Y年m月');
        }
        // 口コミ情報のページネーション
        $reviews = new LengthAwarePaginator(
            $reviews->forPage($request->page, 10),
            $reviews->count(),
            10,
            $request->page,
            ['path' => $request->url()],
        );

        // 店舗の評価値を取得
        $shop_rating = $shop->getShopRating();

        return view(
            'shop_detail',
            compact([
                'shop',
                'reservable_times',
                'reserve_max_number',
                'reviews',
                'shop_rating',
            ])
        );
    }

    // 予約登録処理 =======================================================
    public function reserve(ReserveRequest $request) {
        try {
            DB::transaction(function () use($request) {
                $user_id = Auth::user()->id;
                $shop_id = $request->shop_id;
                $start_at = $request->reserve_time;
                $finish_at = (new carbon($start_at))->addHour(2)->format('H:i');

                Reservation::create([
                    'user_id' => $user_id,
                    'shop_id' => $shop_id,
                    'scheduled_on' => $request->reserve_date,
                    'start_at' => $start_at,
                    'finish_at' => $finish_at,
                    'number' => $request->reserve_number,
                ]);
            });

            return redirect('/done');

        } catch (\Exception $e) {
            Log::error($e);
            return redirect(session('previous_page') ?? '/');
        }

    }

    // 予約完了ページ表示 ==================================================
    public function showThanksReserve() {
        return view('thanks_reserve');
    }

    // マイページ表示 ======================================================
    public function showMypage(Request $request) {
        $user = Auth::user();
        $user_name = $user->name;

        session(['previous_page' => $request->getRequestUri()]);

        // 予約情報の取得
        $reservations = Reservation::where('user_id', $user->id)->get();
        foreach ($reservations as $reservation) {
            $reservation->start_at = (new Carbon($reservation->start_at))->format("H:i");
            $shop_id = $reservation->shop_id;
            $reservation->shop_name = Shop::find($shop_id)->name;
        }

        // 取得した予約情報を「過去or未来」振り分け
        $now = CarbonImmutable::now();
        $past_reservations = $reservations->filter(function($reservation) use ($now) {
            $datetime = $reservation->scheduled_on . " " . $reservation->finish_at;
            $datetime = new Carbon($datetime);
            return $datetime < $now;
        });
        $reservations = $reservations->diff($past_reservations);

        // お気に入り店舗情報の取得
        $favorite_shops = $user->favorite_shops;

        // 店舗の「評価値」「レビュー数」「お気に入り数」を算出
        foreach ($favorite_shops as $favorite_shop) {
            $favorite_shop->rating = $favorite_shop->getShopRating();
            $favorite_shop->reviews_quantity = $favorite_shop->getReviewsQuantity();
            $favorite_shop->favorites_quantity = $favorite_shop->getFavoritesQuantity();
        }

        // 予約変更用パラメータの作成
        $reservable_times = $this->getEqualIntervalTimes('16:00', '21:00', 30);
        $reserve_max_number = 10;

        return view('mypage',
            compact([
                'user_name',
                'reservations',
                'past_reservations',
                'favorite_shops',
                'reservable_times',
                'reserve_max_number',
            ])
        );
    }

    // 予約／お気に入りの削除処理 ==========================================
    public function deleteMyData(Request $request) {
        try {
            DB::transaction(function () use($request) {
                // 予約削除
                if ($request->has('reservation_id')) {
                    Reservation::find($request->reservation_id)->delete();
                }

                // お気に入り削除
                if ($request->has('favorite_shop_id')) {
                    $user_id = Auth::user()->id;
                    $shop_id = $request->favorite_shop_id;
                    Favorite::where('user_id', $user_id)
                        ->where('shop_id', $shop_id)
                        ->delete();
                }
            });
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/mypage');
    }

    // 予約変更処理 ========================================================
    public function updateReserve(ReserveRequest $request) {
        try {
            DB::transaction(function () use ($request) {
                $start_at = $request->reserve_time;
                $finish_at = (new carbon($start_at))->addHour(2)->format('H:i');

                Reservation::find($request->reservation_id)
                    ->update([
                        'scheduled_on' => $request->reserve_date,
                        'start_at' => $start_at,
                        'finish_at' => $finish_at,
                        'number' => $request->reserve_number,
                    ]);
            });
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/mypage');
    }

    // 口コミ投稿／更新処理 ========================================================
    public function storeReview(ReviewRequest $request) {
        try {
            DB::transaction(function () use ($request) {
                $review = Reservation::find($request->reservation_id)->review;
                if ($review) {
                    // 更新処理
                    $review->update([
                        'reservation_id' => $request->reservation_id,
                        'rating' => $request->rating,
                        'title' => $request->title,
                        'comment' => $request->comment,
                    ]);
                } else {
                    // 新規登録処理
                    Review::create([
                        'reservation_id' => $request->reservation_id,
                        'rating' => $request->rating,
                        'title' => $request->title,
                        'comment' => $request->comment,
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/mypage');
    }

    // 等間隔の時間配列取得メソッド ========================================
    private function getEqualIntervalTimes($start_time, $end_time, $span_minute) {
        $span_minute = 30;

        $time = new Carbon($start_time);
        $end = new Carbon($end_time);
        while ($time <= $end) {
            $equal_interval_times[] = $time->format('H:i');
            $time->addMinutes($span_minute);
        }

        return $equal_interval_times;
    }

}

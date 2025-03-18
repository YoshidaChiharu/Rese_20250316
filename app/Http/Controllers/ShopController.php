<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use App\Http\Requests\ReserveRequest;
use App\Http\Requests\ReviewRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Jobs\SendReservedMail;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class ShopController extends Controller
{
    /**
     * 飲食店一覧ページ表示
     *
     * @param Request $request
     * @return void
     */
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
        if (Auth::user()) {
            $favorite_shops = Auth::user()->favoriteShops;
            foreach ($shops as $shop) {
                $shop->favorite_flag = 0;
                foreach ($favorite_shops as $favorite_shop) {
                    if ($shop->id === $favorite_shop->id) {
                        $shop->favorite_flag = 1;
                    }
                }
            }
        }

        // ページネーション
        $shops = new LengthAwarePaginator(
            $shops->forPage($request->page, 20),
            $shops->count(),
            20,
            $request->page,
            ['path' => $request->url()]
        );

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

    /**
     * お気に入り更新処理
     *
     * @param Request $request
     * @return void
     */
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

    /**
     * 飲食店詳細ページ表示
     *
     * @param Request $request
     * @return void
     */
    public function showShopDetail(Request $request) {
        $shop = Shop::find($request->shop_id);

        // 予約時間セレクトボックス用の選択肢作成
        $reservable_times = $shop->getReservableTimes('16:00', '21:00', 30);

        // 予約可能最大人数
        $reserve_max_number = 10;

        // 口コミ情報の取得
        $reviews = $shop->reviews->sortByDesc('updated_at');

        // 口コミ「新規登録可能」「編集可能」「削除可能」各フラグの設定
        // フラグ初期化
        $review_registerable = false;
        $reviews->map(function ($review) use ($request) {
            $review->editable = false;
            $review->deletable = false;
            // 管理者は常に削除可能
            if ($request->user()) {
                if ($request->user()->role_id === 1) {
                    $review->deletable = true;
                }
            }
        });
        // フラグセット
        $user_id = $request->user()->id ?? null;
        if ($user_id) {
            // 該当店舗に対する自身の口コミを取得
            $my_review = $reviews->filter(function($review) use ($user_id) {
                return $review->user_id === $user_id;
            })->first();

            // 編集／削除可能フラグをセット
            if ($my_review) {
                $my_review->editable = true;
                $my_review->deletable = true;
            }

            // 該当店舗に対する自身の過去予約を取得
            $reservations = Reservation::where('user_id', $user_id)
                                        ->where('shop_id', $shop->id)
                                        ->get();
            $past_reservations = $reservations->filter(function($reservation) {
                $datetime = $reservation->scheduled_on . " " . $reservation->start_at;
                $reservation_start_carbon = new Carbon($datetime);
                return $reservation_start_carbon < now();
            });

            // 新規登録可能フラグをセット
            if ($my_review === null && $past_reservations->isNotEmpty() && $request->user()->role_id === 3) {
                $review_registerable = true;
            }
        }

        // 口コミ情報のページネーション
        $reviews = new LengthAwarePaginator(
            $reviews->forPage($request->page, 10),
            $reviews->count(),
            10,
            $request->page,
            ['path' => $request->url()],
        );

        return view(
            'shop_detail',
            compact([
                'shop',
                'reservable_times',
                'reserve_max_number',
                'reviews',
                'review_registerable',
            ])
        );
    }

    /**
     * 予約登録処理
     *
     * @param ReserveRequest $request
     * @return void
     */
    public function reserve(ReserveRequest $request) {
        try {
            DB::beginTransaction();

            $user_id = Auth::user()->id;
            $shop_id = $request->shop_id;
            $start_at = $request->reserve_time;
            $course_duration = 120;     // コース未設定の場合は一律120分に設定
            if($request->reserve_course_id) {
                $course_duration = Course::find($request->reserve_course_id)->duration_minutes;
            }
            $finish_at = (new carbon($start_at))->addMinutes($course_duration)->format('H:i');

            // 予約情報の登録
            $reservation = Reservation::create([
                'user_id' => $user_id,
                'shop_id' => $shop_id,
                'scheduled_on' => $request->reserve_date,
                'start_at' => $start_at,
                'finish_at' => $finish_at,
                'number' => $request->reserve_number,
                'course_id' => $request->reserve_course_id,
                'prepayment' => $request->reserve_prepayment,
                'status' => 0,
            ]);

            // 予約詳細ページへのURL
            $url = request()->getSchemeAndHttpHost() . "/admin/reservation_list/" . $shop_id . "/detail/" . $reservation->id;

            // 上記URLのQRコードを生成
            $renderer = new ImageRenderer(
                new RendererStyle(200),
                new ImagickImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qr_code = base64_encode($writer->writeString($url));

            // QRコードを保存
            $reservation->update([
                'qr_code' => $qr_code,
            ]);

            // 予約完了メール送信
            $url = request()->getSchemeAndHttpHost() . "/mypage/" . $reservation->id . "/qr";
            SendReservedMail::dispatch($reservation, $url);

            DB::commit();

            return redirect('/done')->with([
                'prepayment' => $request->reserve_prepayment,
                'reservation' => $reservation,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect(session('previous_page') ?? '/');
        }

    }

    /**
     * 予約完了ページ表示
     *
     * @return void
     */
    public function showThanksReserve() {
        return view('thanks_reserve');
    }

    /**
     * マイページ表示
     *
     * @param Request $request
     * @return void
     */
    public function showMypage(Request $request) {
        $user = Auth::user();
        $user_name = $user->name;

        session(['previous_page' => $request->getRequestUri()]);

        // 予約情報の取得
        $reservations = Reservation::where('user_id', $user->id)->get();
        foreach ($reservations as $reservation) {
            $reservation->start_at = (new Carbon($reservation->start_at))->format("H:i");
            $reservation->reservable_times = $reservation->shop->getReservableTimes('16:00', '21:00', 30);
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
        $favorite_shops = $user->favoriteShops;

        // 店舗の「評価値」「レビュー数」「お気に入り数」を算出
        foreach ($favorite_shops as $favorite_shop) {
            $favorite_shop->rating = $favorite_shop->getShopRating();
            $favorite_shop->reviews_quantity = $favorite_shop->getReviewsQuantity();
            $favorite_shop->favorites_quantity = $favorite_shop->getFavoritesQuantity();
        }

        // 予約変更用パラメータの作成
        $reserve_max_number = 10;   // 予約人数上限値

        return view('mypage',
            compact([
                'user_name',
                'reservations',
                'past_reservations',
                'favorite_shops',
                'reserve_max_number',
            ])
        );
    }

    /**
     * 予約／お気に入りの削除処理
     *
     * @param Request $request
     * @return void
     */
    public function deleteMyData(Request $request) {
        $user = Auth::user();

        // 予約削除
        if ($request->has('reservation_id')) {
            try {
                DB::transaction(function () use($request, $user) {
                    $reservation = Reservation::find($request->reservation_id);
                    $reservation->delete();

                    // 返金処理＆事前決済フラグを「3:返金済み」へ変更
                    if($reservation->payment_intent_id !== NULL) {
                        $user->refund($reservation->payment_intent_id);

                        $reservation->update([
                            'prepayment' => 3,  // 0:なし 1:決済前 2:決済完了 3:返金済み
                        ]);
                    }

                    // 予約ステータス変更
                    $reservation->update([
                        'status' => 2,  // 0:来店前 1:来店済み 2:予約キャンセル
                    ]);
                });
                $message = Lang::get('message.COMPLETE_DELETE');
                $result = true;
            } catch (\Exception $e) {
                $message = Lang::get('message.ERR_DELETE');
                $result = false;
                Log::error($e);
            }

            return redirect('/mypage')->with([
                'message' => $message,
                'result' => $result,
            ]);
        }

        // お気に入り削除
        if ($request->has('favorite_shop_id')) {
            try {
                Favorite::where('user_id', $user->id)
                        ->where('shop_id', $request->favorite_shop_id)
                        ->delete();
            } catch (\Exception $e) {
                Log::error($e);
            }

            return redirect('/mypage');
        }
    }

    /**
     * 予約変更処理
     *
     * @param ReserveRequest $request
     * @return void
     */
    public function updateReserve(ReserveRequest $request) {
        try {
            DB::transaction(function () use ($request) {
                $start_at = $request->reserve_time;
                $course_duration = 120;     // コース未設定の場合は一律120分に設定
                if($request->reserve_course_id) {
                    $course_duration = Course::find($request->reserve_course_id)->duration_minutes;
                }
                $finish_at = (new carbon($start_at))->addMinutes($course_duration)->format('H:i');

                Reservation::find($request->reservation_id)
                    ->update([
                        'scheduled_on' => $request->reserve_date,
                        'start_at' => $start_at,
                        'finish_at' => $finish_at,
                        'number' => $request->reserve_number,
                        'course_id' => $request->reserve_course_id,
                        'prepayment' => $request->reserve_prepayment,
                    ]);
            });
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/mypage');
    }

    /**
     * 口コミ投稿ページ表示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createReview(Request $request) {
        session(['previous_page' => $request->getRequestUri()]);

        $shop = Shop::find($request->shop_id);
        $user_id = $request->user()->id;

        // URL直打ちによる不正アクセス防止
        $reservations = Reservation::where('user_id', $user_id)
                                    ->where('shop_id', $shop->id)
                                    ->get();
        $past_reservations = $reservations->filter(function($reservation) {
            $datetime = $reservation->scheduled_on . " " . $reservation->start_at;
            $reservation_start_carbon = new Carbon($datetime);
            return $reservation_start_carbon < now();
        });

        if ($past_reservations->isEmpty()) {
            return redirect('/');
        }

        // 店舗の「評価値」「レビュー数」「お気に入り数」を算出
        $shop->rating = $shop->getShopRating();
        $shop->reviews_quantity = $shop->getReviewsQuantity();
        $shop->favorites_quantity = $shop->getFavoritesQuantity();

        // お気に入り登録済みかどうかのフラグを設定
        $shop->favorite_flag = 0;
        $favorite = Favorite::where('user_id', $user_id)->where('shop_id', $shop->id)->first();
        if ($favorite) {
            $shop->favorite_flag = 1;
        }

        // 口コミ情報を取得
        $review = Review::where('user_id', $user_id)->where('shop_id', $shop->id)->first();

        return view('store_shop_review', compact(['shop', 'review']));
    }

    /**
     * 口コミ投稿／更新処理
     *
     * @param ReviewRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function storeReview(ReviewRequest $request) {
        $user_id = $request->user()->id;
        $shop_id = $request->shop_id;

        $param = [
            'user_id' => $user_id,
            'shop_id' => $shop_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        if ($request->file('images')) {
            // サムネイル画像の保存
            // （※複数ファイル選択時は1つ目の画像を保存）
            $images = $request->file('images');
            $image_path = $images[0]->store('public/img');
            $param['image'] = str_replace("public/", "", $image_path);
        }

        $review = Review::where('user_id', $user_id)->where('shop_id', $shop_id)->first();
        // 新規登録
        if ($review === null) {
            $request->validate(['images' => 'required']);
            try {
                Review::create($param);
            } catch (\Exception $e) {
                Log::error($e);
            }
        }

        // 既存口コミの更新
        if ($review) {
            try {
                $review->update($param);
            } catch (\Exception $e) {
                Log::error($e);
            }
        }

        return redirect('/detail/'. $shop_id);
    }

    /**
     * 口コミ削除処理
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroyReview(Request $request) {
        try {
            Review::find($request->review_id)->delete();
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/detail/'. $request->shop_id);
    }

    /**
     * QRコード表示
     *
     * @param Request $request
     * @return void
     */
    public function showReservationQR(Request $request) {
        $qr_code = Reservation::find($request->reservation_id)->qr_code;
        return view('reservation_qr', compact('qr_code'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SendAdminMail;

class AdminController extends Controller
{
    // 店舗代表者アカウント作成ページ表示 ==========================================
    public function createShopOwner() {
        return view('admin.register_shop_owner');
    }

    // 店舗代表者アカウント登録処理 ================================================
    public function storeShopOwner(Request $request) {
        // バリデーション
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:254',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'between:8,20',
                'regex:/^[a-zA-Z0-9-_+@]+$/',
            ],
        ]);

        // 店舗代表者作成
        try {
            DB::transaction(function () use($request) {
                $shop_owner = [
                    'role_id' => '2',
                    'name' => $request->name,
                    'email' => $request->email,
                    'email_verified_at' => now(),
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(10),
                ];
                DB::table('users')->insert($shop_owner);
            });
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/admin/register_shop_owner');
    }

    // 店舗情報作成ページ表示 ======================================================
    public function createShopData(Request $request) {
        // 自身が作成した店舗の情報を取得
        $managing_shops = Auth::user()->managingShops;

        return view('admin.register_shop_data', compact('managing_shops'));
    }

    // 店舗情報登録処理 ============================================================
    public function storeShopData(Request $request)
    {
        $owner_id = Auth::user()->id;

        // バリデーション
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'area' => ['required'],
            'genre' => ['required'],
            'detail' => ['required', 'string','max:1000'],
            'images' => ['required'],
            'prepayment_enabled' => ['required'],
            'courses.*.name' => ['required', 'string', 'max:50'],
            'courses.*.duration_minutes' => ['required'],
            'courses.*.price' => ['required'],
        ]);

        // サムネイル画像の保存
        // （※複数ファイル選択時は1つ目の画像を保存）
        $images = $request->file('images');
        $image_path = $images[0]->store('public/img');
        $image_path = str_replace("public/", "", $image_path);

        // 店舗情報＆コース作成
        try {
            DB::transaction(function () use($request, $owner_id, $image_path) {
                $shop = Shop::create([
                    'owner_id' => $owner_id,
                    'name' => $request->name,
                    'area' => $request->area,
                    'genre' => $request->genre,
                    'detail' => $request->detail,
                    'image' => $image_path,
                    'prepayment_enabled' => $request->prepayment_enabled,
                ]);

                $courses = $request->courses;
                foreach($courses as $course) {
                    Course::create([
                        'shop_id' => $shop->id,
                        'name' => $course['name'],
                        'duration_minutes' => $course['duration_minutes'],
                        'price' => $course['price'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/admin/register_shop_data');
    }

    // 店舗情報編集ページ表示 ======================================================
    public function editShopData(Request $request) {
        // 自身が作成した店舗の情報を取得
        $managing_shops = Auth::user()->managingShops;

        // 編集対象の店舗情報を取得
        $shop = Shop::find($request->shop_id);

        return view('admin.edit_shop_data', compact('managing_shops', 'shop'));
    }

    // 店舗情報編集処理 ============================================================
    public function updateShopData(Request $request) {
        // バリデーション
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'area' => ['required'],
            'genre' => ['required'],
            'detail' => ['required', 'string','max:1000'],
            'prepayment_enabled' => ['required'],
            'courses.*.name' => ['required', 'string', 'max:50'],
            'courses.*.duration_minutes' => ['required'],
            'courses.*.price' => ['required'],
        ]);

        $shop = Shop::find($request->shop_id);

        $param = [
            'name' => $request->name,
            'area' => $request->area,
            'genre' => $request->genre,
            'detail' => $request->detail,
        ];

        // サムネイル画像の保存
        // （※複数ファイル選択時は1つ目の画像を保存）
        $images = $request->file('images');
        if ($images) {
            $image_path = $images[0]->store('public/img');
            $param['image'] = str_replace("public/", "", $image_path);
        }

        // 店舗情報＆コース情報の更新
        try {
            DB::transaction(function () use($shop, $param, $request) {
                // 店舗情報更新
                $shop->update($param);

                // 既存コースの更新 or 削除
                $current_courses = $shop->courses->toArray();
                $new_courses = $request->courses;

                foreach ($current_courses as $current_course) {
                    $is_updated = false;
                    foreach ($new_courses as $new_course) {
                        if (empty($new_course['id'])) { continue; }
                        if ($current_course['id'] == $new_course['id']) {
                            Course::find($current_course['id'])->update([
                                'name' => $new_course['name'],
                                'duration_minutes' => $new_course['duration_minutes'],
                                'price' => $new_course['price'],
                            ]);
                            $is_updated = true;
                        }
                    }
                    if ($is_updated) { continue; }
                    Course::find($current_course['id'])->delete();
                }

                // 新規コースの登録
                foreach($new_courses as $new_course) {
                    if (empty($new_course['id'])) {
                        Course::create([
                            'shop_id' => $shop->id,
                            'name' => $new_course['name'],
                            'duration_minutes' => $new_course['duration_minutes'],
                            'price' => $new_course['price'],
                        ]);
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/admin/edit_shop_data/' . $shop->id);
    }

    // 予約一覧ページ表示 ==========================================================
    public function showReservationList(Request $request) {
        // 自身が作成した店舗の情報を取得
        $managing_shops = Auth::user()->managingShops;

        // 表示対象の店舗情報を取得
        $shop = Shop::find($request->shop_id);

        // カレンダーに表示する年、月を決定
        $now = CarbonImmutable::now();
        $this_year = $request->year ?? $now->format('Y');
        $this_month = $request->month ?? $now->format('m');

        // カレンダーの基準日（最初の日曜日となる日）を決定
        $base_date = Carbon::create($this_year, $this_month);
        while ($base_date->dayOfWeek != 0) {
            $base_date->subDay();
        }

        // タイムスケジュールを表示する日にちを決定（nullの場合は非表示）
        $time_schedule = null;
        if($request->has('time_schedule')) {
            $time_schedule['year'] = $request->time_schedule_year;
            $time_schedule['month'] = $request->time_schedule_month;
            $time_schedule['day'] = $request->time_schedule_day;
            // 併せてCarbonも作成しておく（※後々、タイムスケジュール情報の保存処理で使用）
            $time_schedule_date = Carbon::create($time_schedule['year'],
                                                 $time_schedule['month'],
                                                 $time_schedule['day']);
        }

        // カレンダーの各セルに表示する情報を纏めた2次元配列を作成
        for ($i = 0; $i < 35; $i++) {
            // 各セルの年月日
            $calendar[$i]['year'] = $base_date->format('Y');
            $calendar[$i]['month'] = $base_date->format('m');
            $calendar[$i]['day'] = $base_date->format('j');

            // 過去／現在フラグの設定
            $calendar[$i]['is_today'] = false;
            $calendar[$i]['is_past'] = false;
            if($base_date->format('Y-m-d') == $now->format('Y-m-d')) { $calendar[$i]['is_today'] = true; }
            if($base_date->format('Y-m-d') < $now->format('Y-m-d')) { $calendar[$i]['is_past'] = true; }

            // 予約組数＆人数
            $reservations = $shop->reservations
                                 ->where('scheduled_on', $base_date->format('Y-m-d'));

            $calendar[$i]['reservation_group_num'] = 0;
            $calendar[$i]['reservation_people_num'] = 0;
            foreach ($reservations as $reservation) {
                $calendar[$i]['reservation_group_num']++;
                $calendar[$i]['reservation_people_num'] += $reservation->number;
            }

            // タイムスケジュール表示用の予約情報を保存
            if($time_schedule) {
                if($time_schedule_date == $base_date) {
                    $time_schedule['reservations'] = $reservations;
                    $time_schedule['group_num'] = $calendar[$i]['reservation_group_num'];
                    $time_schedule['people_num'] = $calendar[$i]['reservation_people_num'];
                }
            }

            $base_date->addDay();
        }

        // タイムスケジュール表示用予約情報の中に予約時間(min)を仕込んでおく

        // 前月、翌月の情報を作成
        $base_date = CarbonImmutable::create($this_year, $this_month);
        $prev = $base_date->subMonth();
        $prev_year = $prev->format('Y');
        $prev_month = $prev->format('m');
        $next = $base_date->addMonth();
        $next_year = $next->format('Y');
        $next_month = $next->format('m');

        return view(
            'admin.reservation_list',
            compact([
                'managing_shops',
                'shop',
                'calendar',
                'time_schedule',
                'this_year',
                'this_month',
                'prev_year',
                'prev_month',
                'next_year',
                'next_month',
            ]),
        );
    }

    // お知らせメール作成ページ表示 =================================================
    public function createAdminMail() {
        return view('admin.create_admin_mail');
    }

    // お知らせメール送信処理 =======================================================
    public function sendAdminMail(Request $request) {
        // バリデーション
        $request->validate([
            'subject' => ['required'],
            'main_text' => ['required'],
        ]);

        try {
            // 送信対象は全ユーザー
            $users = User::all();

            // 全ユーザー分のメール送信ジョブを作成してディスパッチ
            $jobs = $users->map(function ($user) use ($request) {
                return new SendAdminMail($user, $request->subject, $request->main_text);
            });
            $batch = Bus::batch($jobs)->dispatch();

            $message = Lang::get('message.SENT_MAIL');
            $is_sent = true;
        } catch (\Exception $e) {
            $message = Lang::get('message.ERR_MAIL');
            $is_sent = false;
            Log::error($e);
        }

        return redirect('/admin/admin_mail')->with([
            'message' => $message,
            'is_sent' => $is_sent,
        ]);
    }

    // 予約詳細ページ表示 ===========================================================
    public function showReservationDetail(Request $request) {
        // 自身が作成した店舗の情報を取得
        $managing_shops = Auth::user()->managingShops;

        // 表示対象の店舗、予約情報を取得
        $shop = Shop::find($request->shop_id);
        $reservation = Reservation::find($request->reservation_id);

        return view('admin.reservation_detail', compact('managing_shops', 'shop', 'reservation'));
    }

    // 予約編集ページ表示 ===========================================================
    public function editReservation(Request $request) {
        // 自身が作成した店舗の情報を取得
        $managing_shops = Auth::user()->managingShops;

        // 表示対象の店舗、予約情報を取得
        $shop = Shop::find($request->shop_id);
        $reservation = Reservation::find($request->reservation_id);

        // 予約変更用パラメータの作成
        $reservable_times = $shop->getReservableTimes('16:00', '21:00', 30);

        return view(
            'admin.reservation_edit',
            compact(
                'managing_shops',
                'shop',
                'reservation',
                'reservable_times',
        ));
    }

    // 予約詳細の編集処理 ===========================================================
    public function updateReservation(Request $request) {
        $start_at = $request->reserve_time;
        $finish_at = (new carbon($start_at))->addHour(2)->format('H:i');

        Reservation::find($request->reservation_id)
        ->update([
            'scheduled_on' => $request->reserve_date,
            'start_at' => $start_at,
            'finish_at' => $finish_at,
            'number' => $request->reserve_number,
        ]);

        return redirect('/admin/reservation_list/' . $request->shop_id . '/detail/' . $request->reservation_id);
    }

    // 予約詳細の来店処理 ===========================================================
    public function visitReservation(Request $request) {
        // 予約ステータス変更
        Reservation::find($request->reservation_id)->update([
            'status' => 1,  // 0:来店前 1:来店済み 2:予約キャンセル
        ]);

        return back();
    }

    // 予約詳細のキャンセル処理 ======================================================
    public function cancelReservation(Request $request) {
        $reservation = Reservation::find($request->reservation_id);
        $user = $reservation->user;

        //予約削除
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

        return redirect('/admin/reservation_list/' . $request->shop_id);
    }

}

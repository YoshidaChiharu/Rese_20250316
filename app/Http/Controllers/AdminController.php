<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ShopDataRequest;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AdminController extends Controller
{
    // 店舗代表者アカウント作成ページ表示 ==========================================
    public function createShopOwner() {
        return view('admin.register_shop_owner');
    }

    // 店舗代表者アカウント登録処理 ================================================
    public function storeShopOwner(Request $request) {
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

        $shop_owner = [
            'role_id' => '2',
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10),
        ];
        DB::table('users')->insert($shop_owner);

        return redirect('/admin/register_shop_owner');
    }

    // 店舗情報作成ページ表示 ======================================================
    public function createShopData(Request $request) {
        $shops = Auth::user()->managingShops;

        return view('admin.register_shop_data', compact('shops'));
    }

    // 店舗情報登録処理 ============================================================
    public function storeShopData(Request $request)
    {
        $owner_id = Auth::user()->id;

        // サムネイル画像の保存
        // （※複数ファイル選択時は1つ目の画像を保存）
        $images = $request->file('images');
        $image_path = $images[0]->store('public/img');
        $image_path = str_replace("public/", "", $image_path);

        Shop::create([
            'owner_id' => $owner_id,
            'name' => $request->name,
            'area' => $request->area,
            'genre' => $request->genre,
            'detail' => $request->detail,
            'image' => $image_path,
        ]);

        return redirect('/admin/register_shop_data');
    }

    // 店舗情報編集ページ表示 ======================================================
    public function editShopData(Request $request) {
        $shops = Auth::user()->managingShops;

        return view('admin.edit_shop_data', compact('shops'));
    }

    // 店舗情報編集処理 ============================================================
    public function updateShopData(Request $request) {
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

        Shop::find($request->shop_id)->update($param);

        return redirect('/admin/edit_shop_data/' . $request->shop_index);
    }

    // 予約一覧ページ表示 ==========================================================
    public function showReservationList(Request $request) {
        $shops = Auth::user()->managingShops;

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
            $shop_id = $shops[$request->shop_index]->id;
            $reservations = Shop::find($shop_id)
                                ->reservations
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
                'shops',
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

}

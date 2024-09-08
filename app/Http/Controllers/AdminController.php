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
    public function createShopData() {
        return view('admin.register_shop_data');
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
    public function editShopData() {
        return view('admin.edit_shop_data');
    }

    // 予約一覧ページ表示 ==========================================================
    public function showReservationList() {
        return view('admin.reservation_list');
    }

}

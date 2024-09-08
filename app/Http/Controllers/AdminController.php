<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
    public function storeShopData()
    {
        dd("店舗情報登録処理");
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

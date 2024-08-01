<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shops;

class ShopController extends Controller
{
    // 飲食店一覧ページ表示
    public function index(Request $request) {
        $shops = Shops::all();
        return view('shop_all', ['shops' => $shops]);
    }

    // 飲食店詳細ページ表示
    public function showShopDetail(Request $request) {
        return view('shop_detail');
    }

    // 予約完了ページ表示
    public function showThanksReserve(Request $request) {
        return view('thanks_reserve');
    }

    // マイページ表示
    public function showMypage(Request $request) {
        return view('mypage');
    }
}

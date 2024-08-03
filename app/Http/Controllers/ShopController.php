<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shops;

class ShopController extends Controller
{
    // 飲食店一覧ページ表示 ================================================
    public function index(Request $request) {
        $shops = Shops::all();

        // areaのみ抽出して配列を作成
        $areas = $shops->pluck('area')->toArray();
        $areas = array_unique($areas);
        // genreのみ抽出して配列を作成
        $genres = $shops->pluck('genre')->toArray();
        $genres = array_unique($genres);

        // 検索処理
        $input_area = $request->area;
        $input_genre = $request->genre;
        $input_name = $request->name;

        if ( !empty($input_area) ) {
            $shops = $shops->filter(function ($shop) use ($input_area) {
                return $shop['area'] === $input_area;
            });
        }
        if (!empty($input_genre)) {
            $shops = $shops->filter(function ($shop) use ($input_genre) {
                return $shop['genre'] === $input_genre;
            });
        }
        if (!empty($input_name)) {
            $shops = $shops->filter(function ($shop) use ($input_name) {
                // return $shop['name'] === $input_name;
                return preg_match("/{$input_name}/", $shop['name']);
            });
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

    // 飲食店詳細ページ表示 ================================================
    public function showShopDetail(Request $request) {
        return view('shop_detail');
    }

    // 予約完了ページ表示 ==================================================
    public function showThanksReserve(Request $request) {
        return view('thanks_reserve');
    }

    // マイページ表示 ======================================================
    public function showMypage(Request $request) {
        return view('mypage');
    }
}

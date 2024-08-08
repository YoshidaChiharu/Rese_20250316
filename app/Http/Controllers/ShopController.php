<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Favorite;
use Auth;

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
            $shops = $shops->filter(function ($shop) use ($input_area) {
                return $shop['area'] === $input_area;
            });
        }
        if ( !empty($input_genre) ) {
            $shops = $shops->filter(function ($shop) use ($input_genre) {
                return $shop['genre'] === $input_genre;
            });
        }
        if ( !empty($input_name) ) {
            $shops = $shops->filter(function ($shop) use ($input_name) {
                return preg_match("/{$input_name}/", $shop['name']);
            });
        }

        // お気に入り登録済み店舗の取得
        $favorite_shops = Auth::user()->shops;
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
        $user_id = Auth::user()->id;
        $shop_id = $request->shop_id;
        $flag = $request->favorite_flag;

        if ($flag == 0) {
            // お気に入り登録
            Favorite::create([
                'user_id' => $user_id,
                'shop_id' => $shop_id,
            ]);
        }
        if ($flag == 1) {
            // お気に入り削除
            Favorite::where('user_id', $user_id)
                      ->where('shop_id', $shop_id)
                      ->delete();
        }

        return redirect('/');
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

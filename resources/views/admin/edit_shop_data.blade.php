@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/edit_shop_data.css') }}">
@endsection

@section('content')
<div class="edit-wrapper">
    <div class="edit-content">
        <div class="edit-content__heading">
            <h2>店舗情報</h2>
        </div>
        <form action="/admin/edit_shop_data" class="edit-content__form" method="post">
            @csrf
            <table class="form-table">
                <tr>
                    <th>店名</th>
                    <td>
                        <input class="form-table__input--text" type="text" value="仙人">
                    </td>
                </tr>
                <tr>
                    <th>エリア</th>
                    <td>
                        <select class="form-table__input--select" name="">
                            <option value="" selected>東京都</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <td>
                        <select class="form-table__input--select" name="">
                            <option value="" selected>寿司</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>紹介文</th>
                    <td>
                        <textarea class="form-table__input--text" rows="5" name="">料理長厳選の食材から作る寿司を用いたコースをぜひお楽しみください。食材・味・価格、お客様の満足度を徹底的に追及したお店です。特別な日のお食事、ビジネス接待まで気軽に使用することができます。</textarea>
                    </td>
                </tr>
                <tr>
                    <th>サムネイル画像</th>
                    <td>
                        <div class="form-table__thumbnail">
                            <img src="https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg" alt="shop">
                        </div>
                        <!-- <div class="form-table__drop-area">
                            <img src="{{ asset('img/camera.svg') }}" alt="camera">
                            <p>写真を追加</p>
                        </div> -->
                        <input class="form-table__input--file" type="file" accept="image/*" value="ファイルを選択" name="photo">
                    </td>
                </tr>
            </table>

            <div class="form__button">
                <button class="form__button-submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection
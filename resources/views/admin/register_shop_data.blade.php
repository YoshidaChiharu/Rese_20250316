@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/register_shop_data.css') }}">
@endsection

@section('content')
<div class="register-wrapper">
    <div class="register-content">
        <div class="register-content__heading">
            <h2>新規登録</h2>
        </div>
        <form action="/admin/register_shop_data" class="register-content__form" method="post">
            @csrf
            <table class="form-table">
                <tr>
                    <th>店名</th>
                    <td>
                        <input class="form-table__input--text" type="text">
                    </td>
                </tr>
                <tr>
                    <th>エリア</th>
                    <td>
                        <select class="form-table__input--select" name="">
                            <option value="">東京都</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <td>
                        <select class="form-table__input--select" name="">
                            <option value="">寿司</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>紹介文</th>
                    <td>
                        <textarea class="form-table__input--text" rows="5" name=""></textarea>
                    </td>
                </tr>
                <tr>
                    <th>サムネイル画像</th>
                    <td>
                        <div class="form-table__drop-area">
                            <img src="{{ asset('img/camera.svg') }}" alt="camera">
                            <p>写真を追加</p>
                        </div>
                        <input class="form-table__input--file" type="file" accept="image/*" value="ファイルを選択" name="photo">
                    </td>
                </tr>
            </table>

            <div class="form__button">
                <button class="form__button-submit">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection
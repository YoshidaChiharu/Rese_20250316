@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/register_shop_from_csv.css') }}">
@endsection

@section('content')
<div class="register-wrapper">
    @if(session('result') === true)
    <div class="message result--true">{{ session('message') }}</div>
    @elseif(session('result') === false)
    <div class="message result--false">{{ session('message') }}</div>
    @endif
    <div class="register-content">
        <div class="register-content__heading">
            <h2>CSVインポート</h2>
        </div>
        <form action="/admin/register_shop_from_csv" class="register-content__form" method="post" enctype="multipart/form-data">
            @csrf
            <table class="form-table">
                @if (count($errors) > 0)
                <tr>
                    <div class="form-table__error">
                        <span>【入力エラー】</span>
                        <ul id="error-message-list">
                            @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </tr>
                @endif
                <tr>
                    <th>CSVファイル</th>
                    <td>
                        <input class="form-table__input--file" id="input-csv" type="file" accept="text/csv" name="csv">
                        <div class="csv-error-message" id="csv-error-message"></div>
                        <div class="csv-preview__box">
                            <div class="csv-preview__head">プレビュー表示</div>
                            <div class="csv-preview__body" id="csv-preview"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>画像ファイル</th>
                    <td>
                        <label class="input-image-button" for="input-image">+ファイル追加</label>
                        <input class="form-table__input--file" id="input-image" type="file" accept="image/png, image/jpeg" name="images[]" multiple hidden>
                        <span onclick="resetImageFiles()" class="input-image-reset-button">リセット</span>
                        <div class="image-name-preview">
                            <div class="image-name-preview__box">
                                <div class="image-name-preview__head">
                                    <span>選択したファイル</span>
                                    <span class="input-image-error hidden" id="input-image-error">※ファイル不足</span>
                                </div>
                                <div class="image-name-preview__body" id="drop-target">
                                    <ul id="input-image-list"></ul>
                                </div>
                            </div>
                            <div class="image-name-preview__box">
                                <div class="image-name-preview__head">
                                    <span>必要なファイル</span>
                                </div>
                                <div class="image-name-preview__body">
                                    <ul id="need-image-list"></ul>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="form__button">
                <button class="form__button-submit" id="js-submit-button">一括登録</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/register_shop_from_csv.js') }}"></script>
@endsection
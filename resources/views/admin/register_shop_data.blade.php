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
        <form action="/admin/register_shop_data" class="register-content__form" method="post" enctype="multipart/form-data">
            @csrf
            <table class="form-table">
                <tr>
                    <th>店名</th>
                    <td>
                        <input class="form-table__input--text" type="text" value="{{ old('name') }}" name="name">
                    </td>
                </tr>
                <tr>
                    <th>エリア</th>
                    <td>
                        <select class="form-table__input--select" name="area">
                            @foreach(config('pref') as $key => $score)
                            <option value="{{ $score }}" {{ $score == old('area') ? 'selected' : '' }}>
                            {{ $score }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <td>
                        <select class="form-table__input--select" name="genre">
                            @foreach(config('genre') as $key => $score)
                            <option value="{{ $score }}" {{ $score == old('genre') ? 'selected' : '' }}>
                            {{ $score }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>紹介文</th>
                    <td>
                        <textarea class="form-table__input--text" rows="5" name="detail">{{ old('detail') }}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>サムネイル画像</th>
                    <td>
                        <div class="form-table__drop-area" id="drop-target">
                            <img class="drop-area__icon" id="drop-area__icon" src="{{ asset('img/camera.svg') }}" alt="camera">
                            <p class="drop-area__text" id="drop-area__text">写真を追加</p>
                            <img class="drop-area__preview" id="drop-area__preview" src="">
                        </div>
                        <input class="form-table__input--file" id="input-file" type="file" accept="image/*" name="images[]">
                    </td>
                </tr>
            </table>

            <div class="form__button">
                <button class="form__button-submit">登録</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/edit_shop_data.js') }}"></script>
@endsection
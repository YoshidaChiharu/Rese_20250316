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
        <form action="/admin/edit_shop_data/{{ $shop->id }}" class="edit-content__form" method="post" enctype="multipart/form-data">
            @csrf
            <table class="form-table">
                <tr>
                    <th>店名</th>
                    <td>
                        <input class="form-table__input--text" type="text" value="{{ old('name', $shop->name) }}" name="name">
                        <div class="form-table__error">
                            @error('name')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>エリア</th>
                    <td>
                        <select class="form-table__input--select" name="area">
                            @foreach(config('pref') as $key => $score)
                            <option value="{{ $score }}" {{ $score == old('area', $shop->area) ? 'selected' : '' }}>
                            {{ $score }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-table__error">
                            @error('area')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <td>
                        <select class="form-table__input--select" name="genre">
                            @foreach(config('genre') as $key => $score)
                            <option value="{{ $score }}" {{ $score == old('genre', $shop->genre) ? 'selected' : '' }}>
                            {{ $score }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-table__error">
                            @error('genre')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>紹介文</th>
                    <td>
                        <textarea class="form-table__input--text" rows="5" name="detail">{{ old('detail', $shop->detail) }}</textarea>
                        <div class="form-table__error">
                            @error('detail')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>サムネイル画像</th>
                    <td>
                        <div class="form-table__drop-area" id="drop-target">
                            <img class="drop-area__preview" id="drop-area__preview" src="{{ asset('storage/' . $shop->image) }}">
                        </div>
                        <input class="form-table__input--file" id="input-file" type="file" accept="image/*" name="images[]">
                        <div class="form-table__error">
                            @error('images')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
            </table>

            <div class="form__button">
                <button class="form__button-submit">更新する</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/edit_shop_data.js') }}"></script>
@endsection
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
                @if (count($errors) > 0)
                <tr>
                    <div class="form-table__error">
                        <span>【入力エラー】</span>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </tr>
                @endif
                <tr>
                    <th>店名</th>
                    <td>
                        <input class="form-table__input--text" type="text" value="{{ old('name', $shop->name) }}" name="name">
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
                    </td>
                </tr>
                <tr>
                    <th>紹介文</th>
                    <td>
                        <textarea class="form-table__input--text" rows="5" name="detail">{{ old('detail', $shop->detail) }}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>サムネイル画像</th>
                    <td>
                        <div class="form-table__drop-area" id="drop-target">
                            <img class="drop-area__preview" id="drop-area__preview" src="{{ asset('storage/' . $shop->image) }}">
                        </div>
                        <input class="form-table__input--file" id="input-file" type="file" accept="image/*" name="images[]">
                    </td>
                </tr>
                <tr>
                    <th>コース設定</th>
                    <td>
                        <ul class="form-table__course-list" id="js-add-course-target">
                            @foreach($shop->courses as $course)
                            <li class="course-list-item">
                                <a href="javascript:void(0)" class="course-button" onclick="deleteCourse(this)">
                                    <img src="{{ asset('img/minus.svg') }}">
                                </a>
                                <input type="text" class="course-list-item__input" placeholder="コース名" name="courses[{{ $loop->index }}][name]" value="{{ $course->name }}">
                                <input type="number" class="course-list-item__input" min="60" max="180" step="30" value="{{ $course->duration_minutes }}" name="courses[{{ $loop->index }}][duration_minutes]">分
                                <input type="number" class="course-list-item__input" min="0" step="1" value="{{ $course->price }}" name="courses[{{ $loop->index }}][price]">円
                                <input type="hidden" name="courses[{{ $loop->index }}][id]" value={{ $course->id }}>
                            </li>
                            @endforeach
                        </ul>
                        <div class="form-table__add-course">
                            <a href="javascript:void(0)" class="course-button" onclick="addCourse()">
                                <img src="{{ asset('img/plus.svg') }}">
                            </a>
                            <span>コース追加</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>事前決済</th>
                    <td>
                        <input type="radio" class="form-table__input--radio" name="prepayment_enabled" value=0 checked>不可
                        <input type="radio" class="form-table__input--radio" name="prepayment_enabled" value=1 {{ old('prepayment_enabled', $shop->prepayment_enabled) === 1 ? 'checked' : '' }}>可
                    </td>
                </tr>
            </table>

            <div class="form__button">
                <button class="form__button-submit" id="js-submit-button">更新する</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/edit_shop_data.js') }}"></script>
@endsection
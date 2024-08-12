@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('content')
<div class="wrapper">
    <div class="shop-info-section">
        <div class="shop-info__name">
            <a class="back-button" href="{{ session('previous_page') ?? '/' }}">
                &lt;
            </a>
            <h2>{{ $shop->name }}</h2>
        </div>
        <div class="shop-info__image">
            <img src="{{ $shop->image }}" alt="shop image">
        </div>
        <div class="shop-info__tag-list">
            <ul>
                <li class="shop-info__tag">#{{ $shop->area }}</li>
                <li class="shop-info__tag">#{{ $shop->genre }}</li>
            </ul>
        </div>
        <div class="shop-info__description">
            <p>{{ $shop->detail }}</p>
        </div>
    </div>
    <div class="reserve-section">
        <form action="/detail/{{ $shop->id }}" class="reserve-form" method="post">
            @csrf
            <div class="form__inner">
                <div class="form__title">
                    <h3>予約</h3>
                </div>
                <input type="hidden" name="shop_id" value={{ $shop->id }}>
                <div class="form__input">
                    <input type="date" name="reserve_date" class="form__input--date" id="form_date" value="{{ old('reserve_date') }}" onchange="setValue(this.value, 'confirm_date')">
                </div>
                <div class="form__input">
                    <select name="reserve_time" class="form__input--select" id="form_time" onchange="setValue(this.value, 'confirm_time')">
                        <option value="">-</option>
                        @foreach ($reservable_times as $reservable_time)
                        <option value="{{ $reservable_time }}" {{ old('reserve_time') == $reservable_time ? 'selected' : '' }}>{{ $reservable_time }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__input">
                    <select name="reserve_number" class="form__input--select" id="form_number" onchange="setValue(this.value, 'confirm_number')">
                        <option value="">-</option>
                        @for ($i = 1; $i <= $reserve_max_number; $i++)
                            <option value="{{$i}}" {{ old('reserve_number') == $i ? 'selected' : '' }}>{{$i}}</option>
                            @endfor
                    </select>
                </div>
                <div class="form__confirm">
                    <table class="confirm-table">
                        <tr>
                            <th>Shop</th>
                            <td>{{ $shop->name }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td id="confirm_date">{{ old('reserve_date') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td id="confirm_time">{{ old('reserve_time') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Number</th>
                            <td id="confirm_number">{{ old('reserve_number') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                @if (count($errors) > 0)
                <div class="form__error">
                    <div class="error__title">
                        <img src="{{ asset('img/error.svg') }}" alt="error">
                        <span>入力エラー</span>
                    </div>
                    <ul class="error__list">
                        @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>
            <div class="form__button">
                <button class="form__button--submit">予約する</button>
            </div>
        </form>
    </div>
</div>
@endsection
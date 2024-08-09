@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('content')
<div class="wrapper">
    <div class="shop-info-section">
        <div class="shop-info__name">
            <a class="back-button" href="/?area={{ session('area') }}&genre={{ session('genre') }}&name={{ session('name') }}"><</a>
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
                <div class="form__input">
                    <input type="date" name="reserve_date" class="form__input--date">
                </div>
                <div class="form__input">
                    <select name="reserve_time" class="form__input--select">
                        <option value="">16:00</option>
                        <option value="">17:00</option>
                        <option value="">18:00</option>
                        <option value="">19:00</option>
                        <option value="">20:00</option>
                        <option value="">21:00</option>
                    </select>
                </div>
                <div class="form__input">
                    <select name="reserve_number" class="form__input--select">
                        <option value="">1人</option>
                        <option value="">2人</option>
                        <option value="">3人</option>
                        <option value="">4人</option>
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
                            <td>2021-07-28</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>17:00</td>
                        </tr>
                        <tr>
                            <th>Number</th>
                            <td>1人</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="form__button">
                <button class="form__button--submit">予約する</button>
            </div>
        </form>
    </div>
</div>
@endsection
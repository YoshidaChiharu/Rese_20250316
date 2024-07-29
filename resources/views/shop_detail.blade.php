@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('content')
<div class="wrapper">
    <div class="shop-info-section">
        <div class="shop-info__name">
            <form action="">
                <button><</button>
            </form>
            <h2>仙人</h2>
        </div>
        <div class="shop-info__image">
            <img src="{{ asset('img/sushi.jpg') }}" alt="shop image">
        </div>
        <div class="shop-info__tag-list">
            <ul>
                <li class="shop-info__tag">#東京都</li>
                <li class="shop-info__tag">#寿司</li>
            </ul>
        </div>
        <div class="shop-info__description">
            <p>料理長厳選の食材から作る寿司を用いたコースをぜひお楽しみください。サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト</p>
        </div>
    </div>
    <div class="reserve-section">
        <form action="" class="reserve-form">
            <div class="form__inner">
                <div class="form__title">
                    <h3>予約</h3>
                </div>
                <div class="form__input">
                    <input type="date" class="form__input--date">
                </div>
                <div class="form__input">
                    <select name="" id="" class="form__input--select">
                        <option value="">16:00</option>
                        <option value="">17:00</option>
                        <option value="">18:00</option>
                    </select>
                </div>
                <div class="form__input">
                    <select name="" id="" class="form__input--select">
                        <option value="">1人</option>
                        <option value="">2人</option>
                        <option value="">3人</option>
                    </select>
                </div>
                <div class="form__confirm">
                    <table class="confirm-table">
                        <tr>
                            <th>Shop</th>
                            <td>仙人</td>
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
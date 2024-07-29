@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="heading">
    <div class="heading__inner">
        <span class="heading__name">testさん</span>
    </div>
</div>
<div class="content">
    <div class="reserve-section">
        <h2 class="section-title">予約状況</h2>
        <div class="reserve-list">
            <div class="reserve-card">
                <div class="reserve-card__title">
                    <img src="{{ asset('img/clock.svg') }}" alt="clock">
                    <span>予約1</span>
                    <form action="">
                        <input type="image" class="reserve-card__button" src="{{ asset('img/cross.svg') }}" alt="cross button">
                    </form>
                </div>
                <table class="reserve-card__table">
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

            <div class="reserve-card">
                <div class="reserve-card__title">
                    <img src="{{ asset('img/clock.svg') }}" alt="clock">
                    <span>予約1</span>
                    <form action="">
                        <input type="image" class="reserve-card__button" src="{{ asset('img/cross.svg') }}" alt="cross button">
                    </form>
                </div>
                <table class="reserve-card__table">
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
    </div>
    <div class="favorite-section">
        <h2 class="section-title">お気に入り店舗</h2>
        <div class="favorite-list">
            <div class="shop-card">
                <div class="card-image">
                    <img src="{{ asset('img/sushi.jpg') }}" alt="shop image">
                </div>
                <div class="card-content">
                    <div class="card-content__name">
                        <h2>サンプルテキストサンプルテキスト</h2>
                    </div>
                    <ul class="card-content__tag-list">
                        <li class="card-content__tag">#東京都</li>
                        <li class="card-content__tag">#寿司</li>
                    </ul>
                    <form action="" class="card-content__form">
                        <button class="form__button--detail">詳しく見る</button>
                        <input type="image" class="form__button--favorite" src="{{ asset('img/heart_gray.svg') }}" alt="favorite">
                    </form>
                </div>
            </div>

            @for($i=0; $i < 6; $i++)
            <div class="shop-card">
                <div class="card-image">
                    <img src="{{ asset('img/sushi.jpg') }}" alt="shop image">
                </div>
                <div class="card-content">
                    <div class="card-content__name">
                        <h2>サンプルテキストサンプルテキスト</h2>
                    </div>
                    <ul class="card-content__tag-list">
                        <li class="card-content__tag">#東京都</li>
                        <li class="card-content__tag">#寿司</li>
                    </ul>
                    <form action="" class="card-content__form">
                        <button class="form__button--detail">詳しく見る</button>
                        <input type="image" class="form__button--favorite" src="{{ asset('img/heart_gray.svg') }}" alt="favorite">
                    </form>
                </div>
            </div>
            @endfor

        </div>
    </div>
</div>
@endsection
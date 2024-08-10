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
            @foreach($reservations as $reservation)
            <div class="reserve-card">
                <div class="reserve-card__title">
                    <img src="{{ asset('img/clock.svg') }}" alt="clock">
                    <span>予約{{ $loop->iteration }}</span>
                    <form action="/mypage" method="post">
                        @csrf
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                        <input type="image" class="reserve-card__button" src="{{ asset('img/cross.svg') }}" alt="cross button">
                    </form>
                </div>
                <table class="reserve-card__table">
                    <tr>
                        <th>Shop</th>
                        <td>{{ $reservation->shop_name }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $reservation->scheduled_on }}</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>{{ $reservation->start_at }}</td>
                    </tr>
                    <tr>
                        <th>Number</th>
                        <td>{{ $reservation->number }}人</td>
                    </tr>
                </table>
            </div>
            @endforeach

        </div>
    </div>
    <div class="favorite-section">
        <h2 class="section-title">お気に入り店舗</h2>
        <div class="favorite-list">
            @foreach($favorite_shops as $shop)
            <div class="shop-card">
                <div class="card-image">
                    <img src="{{ $shop->image }}" alt="shop image">
                </div>
                <div class="card-content">
                    <div class="card-content__name">
                        <h2>{{ $shop->name }}</h2>
                    </div>
                    <ul class="card-content__tag-list">
                        <li class="card-content__tag">#{{ $shop->area }}</li>
                        <li class="card-content__tag">#{{ $shop->ganre }}</li>
                    </ul>
                    <div class="card-content__button">
                        <a class="button-detail" href="/detail/{{ $shop->id }}">詳しく見る</a>
                        <form action="/mypage" method="post">
                            @csrf
                            <input type="hidden" name="favorite_shop_id" value="{{ $shop->id }}">
                            <input type="image" class="button-favorite" src="{{ asset('img/heart_red.svg') }}" alt="favorite">
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection
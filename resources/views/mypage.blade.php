@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="heading">
    <div class="heading__inner">
        <span class="heading__name">{{ $user_name }}さん</span>
    </div>
</div>
<div class="content">
    <div class="reserve-section">
        <h2 class="section-title">予約状況</h2>
        <div class="reserve-list">
            @foreach($reservations as $reservation)

            @if (Request::get('change_id') != $reservation->id)
            <!-- 通常時 -->
            <div class="reserve-card">
                <div class="reserve-card__inner">
                    <div class="reserve-card__title">
                        <div>
                            <img src="{{ asset('img/clock.svg') }}" alt="clock">
                            <span>予約{{ $loop->iteration }}</span>
                        </div>
                        <form action="/mypage" method="post">
                            @csrf
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                            <input type="image" class="reserve-card__button--delete" src="{{ asset('img/cross.svg') }}" alt="cross button">
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
                <div>
                    <button class="reserve-card__button--change" onclick="location.href='/mypage?change_id={{ $reservation->id }}'">予約内容の変更</button>
                </div>
            </div>
            @else
            <!-- 予約変更時 -->
            <div class="reserve-card bg-orange">
                <form action="/mypage/update_reserve" method="post">
                    @csrf
                    <div class="reserve-card__inner">
                        <div class="reserve-card__title">
                            <div>
                                <img src="{{ asset('img/clock.svg') }}" alt="clock">
                                <span>予約{{ $loop->iteration }}：予約内容の修正</span>
                            </div>
                        </div>
                        <table class="reserve-card__table">
                            <tr>
                                <th>Shop</th>
                                <td>{{ $reservation->shop_name }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>
                                    <input type="date" name="reserve_date" value="{{ old('reserve_date') ?? $reservation->scheduled_on }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Time</th>
                                <td>
                                    <select name="reserve_time">
                                        <option value="">-</option>
                                        @foreach ($reservable_times as $reservable_time)
                                        <option value="{{ $reservable_time }}" {{ old('reserve_time') ?? $reservation->start_at == $reservable_time ? 'selected' : '' }}>{{ $reservable_time }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Number</th>
                                <td>
                                    <select name="reserve_number">
                                        <option value="">-</option>
                                        @for ($i = 1; $i <= $reserve_max_number; $i++)
                                            <option value="{{$i}}" {{ old('reserve_number') ?? $reservation->number == $i ? 'selected' : '' }}>{{$i}}</option>
                                            @endfor
                                    </select>
                                </td>
                            </tr>
                        </table>
                        @if (count($errors) > 0)
                        <div class="reserve-card__error">
                            <span>※入力エラー</span>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div>
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                        <button class="reserve-card__button--change button-orange">変更する</button>
                    </div>
                </form>
            </div>
            @endif

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
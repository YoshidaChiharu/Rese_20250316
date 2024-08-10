@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('content')
<div class="wrapper">
    <div class="shop-info-section">
        <div class="shop-info__name">
            <!-- <a class="back-button" href="/?area={{ session('area') }}&genre={{ session('genre') }}&name={{ session('name') }}">
                &lt;
            </a> -->
            <a class="back-button" href="{{ session('previous_page') }}">
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
                    <input type="date" name="reserve_date" class="form__input--date" id="form_date" onchange="setDate(this.value)">
                </div>
                <div class="form__input">
                    <select name="reserve_time" class="form__input--select" id="form_time" onchange="setTime(this.value)">
                        <option value="">-</option>
                        @for($i = 16; $i <= 22; $i++) <option value="{{$i}}:00">{{$i}}:00</option> @endfor
                    </select>
                </div>
                <div class="form__input">
                    <select name="reserve_number" class="form__input--select" id="form_number" onchange="setNumber(this.value)">
                        <option value="">-</option>
                        @for($i = 1; $i <= 5; $i++) <option value="{{$i}}">{{$i}}</option> @endfor
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
                            <td id="confirm_date">-</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td id="confirm_time">-</td>
                        </tr>
                        <tr>
                            <th>Number</th>
                            <td id="confirm_number">-</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="form__button">
                <button class="form__button--submit">予約する</button>
            </div>

            <script>
                function setDate(value) {
                    document.getElementById('confirm_date').textContent = value;
                }

                function setTime(value) {
                    document.getElementById('confirm_time').textContent = value;
                }

                function setNumber(value) {
                    document.getElementById('confirm_number').textContent = value;
                }
            </script>

        </form>
    </div>
</div>
@endsection
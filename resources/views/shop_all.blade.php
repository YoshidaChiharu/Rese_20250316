@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_all.css') }}">
@endsection

@section('content')
<div class="search-section">
    <form action="" class="search-form">
        <div class="form__area">
            <select name="area" onchange="submit(this.form)">
                <option value="">All area</option>
                @foreach($areas as $area)
                <option value="{{ $area }}" {{ $area == $input_area ? 'selected' : '' }}>
                    {{ $area }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form__genre">
            <select name="genre" onchange="submit(this.form)">
                <option value="">All genre</option>
                @foreach($genres as $genre)
                <option value="{{ $genre }}" {{ $genre == $input_genre ? 'selected' : '' }}>
                    {{ $genre }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form__name">
            <img src="{{ asset('img/search.svg') }}" alt="search">
            <input type="text" name="name" placeholder="Search ..." onchange="submit(this.form)" value="{{ $input_name }}">
        </div>
    </form>
</div>
<div class="shop-list-section">
    @foreach($shops as $shop)
    <div class="shop-card">
        <div class="card-image">
            <img src="{{ asset('storage/' . $shop->image) }}" alt="shop image">
        </div>
        <div class="card-content">
            <div class="card-content__name">
                <h2>{{ $shop->name }}</h2>
            </div>
            <ul class="card-content__tag-list">
                <li class="card-content__tag">#{{ $shop->area }}</li>
                <li class="card-content__tag">#{{ $shop->genre }}</li>
            </ul>
            <div class="card-content__rating">
                @for ($i=1; $i<=5 ; $i++)
                    @if($i <=$shop->rating)
                    <img src="{{ asset('img/star_on_gold.svg') }}" class="image--star" alt="star">
                    @elseif(($i - $shop->rating) < 0.5)
                        <img src="{{ asset('img/star_on_half.png') }}" class="image--star" alt="star">
                        @else
                        <img src="{{ asset('img/star_on_gray.svg') }}" class="image--star" alt="star">
                        @endif
                        @endfor
                        <span class="rating-value">{{ $shop->rating }}</span>
            </div>
            <div class="card-content__info">
                <img src="{{ asset('img/speech_bubble_beige.svg') }}" alt="speech_bubble">
                <span>{{ $shop->reviews_quantity }}人</span>
                <img src="{{ asset('img/heart_beige.svg') }}" alt="heart">
                <span>{{ $shop->favorites_quantity }}人</span>
            </div>
            <div class="card-content__button">
                <a class="button-detail" href="/detail/{{ $shop->id }}">詳しく見る</a>
                <form action="/" method="post">
                    @csrf
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    <input type="hidden" name="favorite_flag" value="{{ $shop->favorite_flag }}">
                    @if ($shop->favorite_flag === 0)
                    <input type="image" class="button-favorite" src="{{ asset('img/heart_gray.svg') }}" alt="favorite">
                    @else
                    <input type="image" class="button-favorite" src="{{ asset('img/heart_red.svg') }}" alt="favorite">
                    @endif
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
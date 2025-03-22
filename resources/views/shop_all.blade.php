@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_all.css') }}">
@endsection

@section('script')
<script src="{{ asset('js/shop_all.js') }}"></script>
@endsection

@section('content')
<div class="search-section">
    <form action="" class="search-form">
        <div class="form__sort">
            <span>並び替え：</span>
            <select name="sort" onchange="submit(this.form)">
                <option value=""></option>
                <option value="ランダム" {{ $input_sort == 'ランダム' ? 'selected' : '' }}>ランダム</option>
                <option value="評価が高い順" {{ $input_sort == '評価が高い順' ? 'selected' : '' }}>評価が高い順</option>
                <option value="評価が低い順" {{ $input_sort == '評価が低い順' ? 'selected' : '' }}>評価が低い順</option>
            </select>
        </div>
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
<div class="shop-list-top">
    <div class="search-info">
        @if($input_sort || $input_area || $input_genre || $input_name)
        <span>検索情報：</span>
        @endif
        @if($input_sort) <span>"{{ $input_sort }}"&nbsp;</span> @endif
        @if($input_area) <span>"{{ $input_area }}"&nbsp;</span> @endif
        @if($input_genre) <span>"{{ $input_genre }}"&nbsp;</span> @endif
        @if($input_name) <span>"{{ $input_name }}"</span> @endif
        </div>
    <div class="pagination">{{ $shops->appends(request()->query())->onEachSide(1)->links('vendor.pagination.default') }}</div>
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
                <span class="rating-value">
                    @if($shop->rating == 0)
                    投稿なし
                    @else
                    {{ $shop->rating }}
                    @endif
                </span>
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
                    @if ($shop->favorite_flag === 1)
                    <input type="image" class="button-favorite" src="{{ asset('img/heart_red.svg') }}" alt="favorite">
                    @else
                    <input type="image" class="button-favorite" src="{{ asset('img/heart_gray.svg') }}" alt="favorite">
                    @endif
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
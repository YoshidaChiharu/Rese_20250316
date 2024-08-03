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
            <img src="{{ $shop->image }}" alt="shop image">
        </div>
        <div class="card-content">
            <div class="card-content__name">
                <h2>{{ $shop->name }}</h2>
            </div>
            <ul class="card-content__tag-list">
                <li class="card-content__tag">#{{ $shop->area }}</li>
                <li class="card-content__tag">#{{ $shop->genre }}</li>
            </ul>
            <form action="" class="card-content__form">
                <button class="form__button--detail">詳しく見る</button>
                <input type="image" class="form__button--favorite" src="{{ asset('img/heart_gray.svg') }}" alt="favorite">
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
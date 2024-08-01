@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_all.css') }}">
@endsection

@section('content')
<div class="search-section">
    <form action="" class="search-form">
        <div class="form__area">
            <select name="" id="">
                <option value="">All area</option>
                <option value="">area1</option>
                <option value="">area2</option>
                <option value="">area3</option>
            </select>
        </div>
        <div class="form__genre">
            <select name="" id="">
                <option value="">All genre</option>
                <option value="">genre1</option>
                <option value="">genre2</option>
                <option value="">genre3</option>
            </select>
        </div>
        <div class="form__name">
            <img src="{{ asset('img/search.svg') }}" alt="search">
            <input type="text" placeholder="Search ...">
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
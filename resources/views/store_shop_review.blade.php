@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store_shop_review.css') }}">
@endsection

@section('script')
<script src="{{ asset('js/store_shop_review.js') }}"></script>
@endsection

@section('content')
<div class="top-wrapper">
    <div class="shop-info-section">
        <div class="shop-info-section__body">
            <div class="shop-info-section__message">
                <span>今回のご利用はいかがでしたか？</span>
            </div>
            <div class="shop-info-section__shop-info">
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
            </div>
        </div>
    </div>
    <div class="review-section">
        <div class="review-section__body">
            <form id="review-from" action="{{'/review/'.$shop->id}}" method="post" enctype="multipart/form-data">
                @csrf
                @if (count($errors) > 0)
                <div class="form-error">
                    <span>※入力エラー</span>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="review-section__rating">
                    <p class="review-section__heading-text">体験を評価してください</p>
                    <input type="hidden" id="rating" name="rating" value="{{ old('rating', $review->rating ?? 0) }}">
                    <div class="review_stars">
                        @for ($i=1; $i<=5 ; $i++)
                        @if ($i <= old('rating', $review->rating ?? 0))
                        <input type="image" class="review__input--star" src="{{ asset('img/star_on_blue.svg') }}" alt="star" value="{{ $i }}" onclick="return changeStar(this.value)">
                        @else
                        <input type="image" class="review__input--star" src="{{ asset('img/star_off_blue.svg') }}" alt="star" value="{{ $i }}" onclick="return changeStar(this.value)">
                        @endif
                        @endfor
                    </div>
                </div>
                <div class="review-section__comment">
                    <p class="review-section__heading-text">口コミを投稿</p>
                    <textarea class="review__input--comment" name="comment" rows="5" placeholder="カジュアルな夜のお出かけにおすすめのスポット" id="input-comment">{{ old('comment', $review->comment ?? '') }}</textarea>
                    <div class="input-notice">
                        <span id="comment-length">0/400（最高文字数）</span>
                    </div>
                </div>
                <div class="review-section__image">
                    <p class="review-section__heading-text">画像の追加</p>
                    <label for="input-file">
                        <div class="drop-area" id="drop-target">
                            @if ($review)
                            <img class="drop-area__preview" id="drop-area__preview" src="{{ asset('storage/' . $review->image) }}">
                            @else
                            <img class="drop-area__preview" id="drop-area__preview" src="">
                            <p class="drop-area__text" id="drop-area__text">クリックして写真を追加<br>またはドラッグアンドドロップ</p>
                            @endif
                        </div>
                    </label>
                    <input class="hidden" id="input-file" type="file" accept="image/*" name="images[]">
                    <div class="input-notice">
                        <span>最大サイズ : 10MB</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="bottom-wrapper">
    <button class="button-submit" form="review-from">口コミを投稿</button>
</div>
@endsection
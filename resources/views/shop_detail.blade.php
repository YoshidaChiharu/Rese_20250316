@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('script')
<script src="{{ asset('js/shop_detail.js') }}"></script>
@endsection

@section('content')
<div class="top-wrapper">
    <div class="left-block">
        <div class="shop-info-section">
            <div class="shop-info__name">
                <a class="back-button" href="{{ session('previous_page') ?? '/' }}">
                    &lt;
                </a>
                <h2>{{ $shop->name }}</h2>
            </div>
            <div class="shop-info__image">
                <img src="{{ asset('storage/' . $shop->image) }}" alt="shop image">
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

        @if ($review_registerable)
        <div class="shop-info__review-link">
            <a href="{{'/review/'.$shop->id}}">口コミを投稿する</a>
        </div>
        @endif

        @if($reviews->isNotEmpty())
        <div class="review-section">
            <div class="review-section__heading">
                <h3 class="heading__text">全ての口コミ情報</h3>
            </div>
            <div class="review-section__body">
                <div class="body-top">
                    <div class="review-pagination">
                        {{ $reviews->links('vendor.pagination.default') }}
                    </div>
                </div>
                @foreach($reviews as $review)
                <div class="review-item">
                    <div class="review-item__review-content">
                        <div class="review-content__edit-delete">
                            @if($review->editable)
                            <a href="{{'/review/'.$shop->id}}" class="edit-delete__button">口コミを編集</a>
                            @endif
                            @if($review->deletable)
                            <a href="#review-delete-modal-{{ $review->id }}" class="edit-delete__button">口コミを削除</a>

                            <!-- 口コミ削除確認モーダル -->
                            <div class="review-delete-modal" id="review-delete-modal-{{ $review->id }}">
                                <div class="review-delete-modal-outer">
                                    <div class="review-delete-modal-inner">
                                        <p class="review-delete-modal__message">
                                            口コミを削除します。よろしいですか？
                                        </p>
                                        <div class="review-delete-modal__button-wrapper">
                                            <form action="{{'/review/'.$shop->id.'?review_id='.$review->id}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="review-delete-modal__button--submit">削除</button>
                                            </form>
                                            <a href="" class="review-delete-modal__button--cancel">キャンセル</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="review-content__rating">
                            @for ($i=1; $i<=5 ; $i++)
                                @if($i <= $review->rating)
                                <img src="{{ asset('img/star_on_blue.svg') }}" class="image--star" alt="star">
                                @else
                                <img src="{{ asset('img/star_off_blue.svg') }}" class="image--star" alt="star">
                                @endif
                            @endfor
                        </div>
                        <div class="review-content__comment">
                            <p>{{ $review->comment }}</p>
                        </div>
                        <div class="review-content__image">
                            <img src="{{ asset('storage/' . $review->image) }}" alt="">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="right-block">
        <div class="reserve-section">
            <form action="/detail/{{ $shop->id }}" class="reserve-form" method="post">
                @csrf
                <div class="form__inner">
                    <div class="form__title">
                        <h3>予約</h3>
                    </div>
                    <input type="hidden" name="shop_id" id="js_shop_id" value="{{ $shop->id }}">
                    <div class="form__input">
                        <input type="date" name="reserve_date" class="form__input--date" id="form_date" value="{{ old('reserve_date') }}" onchange="setValue(this.value, 'confirm_date')">
                    </div>
                    <div class="form__input">
                        <select name="reserve_time" class="form__input--select" id="form_time" onchange="setValue(this.value, 'confirm_time')">
                            <option value="">予約時間</option>
                            @foreach ($reservable_times as $reservable_time)
                            <option value="{{ $reservable_time }}" {{ old('reserve_time') == $reservable_time ? 'selected' : '' }}>{{ $reservable_time }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form__input">
                        <select name="reserve_number" class="form__input--select" id="form_number" onchange="setValue(this.value, 'confirm_number')">
                            <option value="">予約人数</option>
                            @for ($i = 1; $i <= $reserve_max_number; $i++)
                            <option value="{{$i}}" {{ old('reserve_number') == $i ? 'selected' : '' }}>{{$i}}人</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form__input">
                        <select name="reserve_course_id" class="form__input--select" id="form_course" onchange="setCourseValue({{$shop->courses}}, this.value)">
                            <option value="">コース</option>
                            @foreach ($shop->courses as $course)
                            <option value="{{ $course->id }}" {{ old('reserve_course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form__input">
                        <select name="reserve_prepayment" class="form__input--select" id="form_prepayment" onchange="setValue(this.options[this.selectedIndex].textContent, 'confirm_prepayment')">
                            <option value="">お支払い方法</option>
                            <option value=0 {{ old('reserve_prepayment') === "0" ? 'selected' : '' }}>店舗でのお支払い</option>
                            @if($shop->prepayment_enabled == 1)
                            <option value=1 {{ old('reserve_prepayment') === "1" ? 'selected' : '' }}>事前決済</option>
                            @endif
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
                                <td id="confirm_date">{{ old('reserve_date') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Time</th>
                                <td id="confirm_time">{{ old('reserve_time') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Number</th>
                                <td id="confirm_number">{{ old('reserve_number') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Course</th>
                                <td id="confirm_course">
                                @if(old('reserve_course_id'))
                                @foreach ($shop->courses as $course)
                                {{ old('reserve_course_id') == $course->id ? $course->name : '' }}
                                @endforeach
                                @else
                                -
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Duration</th>
                                <td id="confirm_duration">
                                @if(old('reserve_course_id'))
                                @foreach ($shop->courses as $course)
                                {{ old('reserve_course_id') == $course->id ? $course->duration_minutes : '' }}
                                @endforeach
                                分
                                @else
                                -
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td id="confirm_price">
                                @if(old('reserve_course_id'))
                                @foreach ($shop->courses as $course)
                                {{ old('reserve_course_id') == $course->id ? number_format($course->price) : '' }}
                                @endforeach
                                円 × 人数分
                                @else
                                -
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Payment</th>
                                <td id="confirm_prepayment">
                                    @if(old('reserve_prepayment') === "0")
                                    店舗でのお支払い
                                    @elseif(old('reserve_prepayment') === "1")
                                    事前決済
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    @if (count($errors) > 0)
                    <div class="form__error">
                        <div class="error__title">
                            <img src="{{ asset('img/error.svg') }}" alt="error">
                            <span>入力エラー</span>
                        </div>
                        <ul class="error__list">
                            @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                </div>
                <div class="form__button">
                    <button class="form__button--submit" id="js-submit-button">予約する</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
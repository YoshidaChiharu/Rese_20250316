@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class="card-wrapper">
    @if (session('message'))
    <div class="error-message">
        <div class="error-message__title">
            <img src="{{ asset('img/error.svg') }}" alt="error">
            <span>決済エラー</span>
        </div>
        <div class="error-message__text">
            {{ __(session('message')) ?? session('message') }}
        </div>
    </div>
    @endif
    <div class="card">
        <div class="card-header">Stripe決済</div>
        <div class="card-body">
            <div class="card-price">
                <span class="card-price__title">合計金額</span><br>
                <span class="card-price__amount">&yen;{{ number_format($reservation->course->price * $reservation->number) }}</span>
            </div>
            <form id="card-form" action="/purchase/{{ $reservation->id }}" method="post">
                @csrf
                <div>
                    <label for="card-number">カード番号</label>
                    <div id="card-number" class="form-control"></div>
                </div>
                <div>
                    <label for="card-expiry">有効期限</label>
                    <div id="card-expiry" class="form-control"></div>
                </div>
                <div>
                    <label for="card-cvc">セキュリティコード</label>
                    <div id="card-cvc" class="form-control"></div>
                </div>
                <div id="card-errors" class="text-danger"></div>
                <button class="form-button" id="card-button">支払い</button>
            </form>
        </div>
    </div>
</div>

<div id="js-stripe-key" stripe_key="{{ config('stripe.stripe_key') }}"></div>
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/payment.js') }}"></script>
@endsection
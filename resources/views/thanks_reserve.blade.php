@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks_reserve.css') }}">
@endsection

@section('content')
<div class="thanks-wrapper">
    <div class="thanks-content">
        <p class="thanks-content__text">ご予約ありがとうございます</p>
        <button class="thanks-content__button">戻る</button>
    </div>
</div>
@endsection
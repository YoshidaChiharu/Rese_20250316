@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks_reserve.css') }}">
@endsection

@section('content')
<div class="thanks-wrapper">
    <div class="thanks-content">
        <p class="thanks-content__text">ご予約ありがとうございます</p>
        <a class="thanks-content__button" href="/">戻る</a>
    </div>
</div>
@endsection
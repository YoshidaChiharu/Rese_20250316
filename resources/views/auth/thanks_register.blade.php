@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/thanks_register.css') }}">
@endsection

@section('content')
<div class="thanks-wrapper">
    <div class="thanks-content">
        <p class="thanks-content__text">会員登録ありがとうございます</p>
        <button class="thanks-content__button">ログインする</button>
    </div>
</div>
@endsection
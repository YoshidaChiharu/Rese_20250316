@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks_reserve.css') }}">
@endsection

@section('content')
<div class="thanks-wrapper">
    <div class="thanks-content">
        <p class="thanks-content__text">ご予約ありがとうございます</p>
        <p class="thanks-content__text">
            ご登録のメールアドレスへ予約完了メール<br>
            を送信しましたので、ご確認ください
        </p>
        <a class="thanks-content__button" href="/">戻る</a>
    </div>
</div>
@endsection
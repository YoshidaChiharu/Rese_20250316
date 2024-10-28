@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase_completed.css') }}">
@endsection

@section('content')
<div class="thanks-wrapper">
    <div class="thanks-content">
        <p class="thanks-content__text">事前決済が完了しました</p>
        <p class="thanks-content__text">
            ご登録のメールアドレスへ決済完了メールを送信しましたので、ご確認ください
        </p>
        <a class="thanks-content__button" href="/">戻る</a>
    </div>
</div>
@endsection
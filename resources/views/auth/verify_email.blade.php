@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify_email.css') }}">
@endsection

@section('content')
<div class="verify-email-wrapper">
    <div class="verify-email-content">
        <p class="verify-email-content__heading">会員登録はまだ完了していません。</p>
        <p class="verify-email-content__text">
            ご登録のメールアドレスへ認証用のメールを送信しました。<br>
            ご確認いただき、メールに記載されたボタンをクリックして、<br>
            会員登録を完了してください。
        </p>
        <form class="verify-email-content__form" action="/email/verification-notification" method="post">
            @csrf
            <button class="form__mail-button">メール再送信</button>
        </form>
        @if (session('status') == 'verification-link-sent')
        <div class="verify-email-content__sent-email">
            新しい認証用メールを送信しました。
        </div>
        @endif
    </div>
</div>
@endsection
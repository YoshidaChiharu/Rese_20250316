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
            ご確認いただき、メールに記載されたボタンをクリックして、
            会員登録を完了してください。
        </p>
        <p class="verify-email-content__notification">
            ＜注意事項＞<br>
            ボタン押下時、メールアプリや別のブラウザでページ表示される場合、認証処理が完了出来ません。
            その場合はメール最下部に記載されているURLをコピーし、このブラウザのURL入力欄に直接貼り付けて認証を行って下さい。
        </p>
        <form class="verify-email-content__form" action="/email/verification-notification" method="post">
            @csrf
            <button class="form__mail-button" id="js-submit-button">メール再送信</button>
        </form>
        @if (session('status') == 'verification-link-sent')
        <div class="verify-email-content__sent-email">
            新しい認証用メールを送信しました。
        </div>
        @endif
    </div>
</div>
@endsection
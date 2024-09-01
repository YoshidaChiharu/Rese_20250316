<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/mail_announce.css') }}">
    <title>Rese</title>
</head>

<body>
    <main>
        <div class="container">
            <div class="container__heading">ログイン認証メールを送信しました</div>
            <div class="container__content">
                <div class="announce-text">
                    <p>
                        ご登録のメールアドレス&nbsp;{{ session('url') }}&nbsp;へログイン認証用のメールを送信しました。<br>
                        ご確認いただき、メールに記載された URL をクリックして、ログインを完了してください。
                    </p>
                </div>
                <div class="notes-text">
                    <p class="notes-text__heading">&lt;メールが届かない場合&gt;</p>
                    <ul>
                        <li>迷惑メールフォルダに振り分けられていたり、フィルターや転送の設定によって受信ボックス以外の場所に保管されていないかご確認ください</li>
                        <li>メールの配信に時間がかかる場合がございます。数分程度待った上で、メールが届いていないか再度ご確認ください</li>
                        <li>
                            登録時にご使用のメールアドレスが正しいかご確認ください。正しくない場合は再度「会員登録」からやり直してください
                            <div class="register">
                                <a href="/register" class="register-link">&gt;&nbsp;会員登録</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
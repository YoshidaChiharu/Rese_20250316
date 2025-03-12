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
    <link rel="stylesheet" href="{{ asset('css/auth/auth_error.css') }}">
    <title>Rese</title>
</head>

<body>
    <main>
        <div class="container">
            <div class="container__heading">認証エラー</div>
            <div class="container__content">
                <p class="error-text">{{ session('message') }}</p>
                <div class="login">
                    <p class="login-text">再度ログインをお試しください</p>
                    <a href="/login" class="login-link">ログイン</a>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
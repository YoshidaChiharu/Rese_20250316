<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/app_admin.css') }}">
    @yield('css')
    <title>Rese</title>
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__logo">
                <h1><a href="/">Rese</a></h1>
            </div>
            <div class="header__text">管理画面</div>
            <div class="header__role">
                @if (Auth::user()->role_id == 1)
                &lt;Administrator&gt;
                @endif
                @if (Auth::user()->role_id == 2)
                &lt;Shop owner&gt;
                @endif
            </div>
        </div>
    </header>
    <main class="main">
        <div class="side-menu">
            <ul class="side-menu__list">
                <li class="menu-list-item">
                    <img class="menu-icon" src="{{ asset('img/home.svg') }}" alt="home">
                    <a href="/">サイトトップへ</a>
                </li>
                @if (Auth::user()->role_id == 1)
                <li class="menu-list-item">
                    <img class="menu-icon" src="{{ asset('img/register.svg') }}" alt="home">
                    <a href="/admin/register_shop_owner">店舗代表者登録</a>
                </li>
                <li class="menu-list-item">
                    <img class="menu-icon" src="{{ asset('img/mail_white.svg') }}" alt="home">
                    <a href="/admin/admin_mail">お知らせメール</a>
                </li>
                @endif
                @if (Auth::user()->role_id == 2)
                <li class="menu-list-item">
                    <img class="menu-icon"  src="{{ asset('img/register.svg') }}" alt="home">
                    <a href="/admin/register_shop_data">新規登録</a>
                </li>
                <li class="menu-list-item">
                    <details>
                        <summary>
                            <span class="summary__inner">
                                <img class="menu-icon"  src="{{ asset('img/shop.svg') }}" alt="home">
                                店舗情報の編集
                                <img class="open-close-icon" src="{{ asset('img/arrow.svg') }}">
                            </span>
                        </summary>
                        <ul class="shop-list">
                            @foreach($shops as $shop)
                            <li>
                                <a href="/admin/edit_shop_data/{{ $loop->index }}">
                                {{ $shop->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </details>
                </li>
                <li class="menu-list-item">
                    <details>
                        <summary>
                            <span class="summary__inner">
                                <img class="menu-icon"  src="{{ asset('img/reservation.svg') }}" alt="home">
                                予約一覧
                                <img class="open-close-icon" src="{{ asset('img/arrow.svg') }}">
                            </span>
                        </summary>
                        <ul class="shop-list">
                            @foreach($shops as $shop)
                            <li>
                                <a href="/admin/reservation_list/{{ $loop->index }}">
                                {{ $shop->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </details>
                </li>
                @endif
            </ul>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </main>
</body>

</html>
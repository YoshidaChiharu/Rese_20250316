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
    @yield('css')
    <title>Rese</title>
</head>

<body>
    <main>
        <div class="container">

            <div class="menu">
                <div class="menu__icon">
                    <a href="#modal-wrapper">
                        <img src="{{ asset('img/bars_1.png') }}" alt="menu">
                    </a>
                </div>
                <div class="menu__logo">
                    <h1><a href="/">Rese</a></h1>
                </div>
            </div>

            <div class="modal-wrapper" id="modal-wrapper">
                <div class="non-scroll"></div>
                <div class="modal">
                    <div class="modal__close-button">
                        <a href="">
                            <img src="{{ asset('img/cross_1.png') }}" alt="close">
                        </a>
                    </div>
                    @if (Auth::check())
                    <div class="modal__list">
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li>
                                <form action="/logout" method="post">
                                    @csrf
                                    <button>Logout</button>
                                </form>
                            </li>
                            <li><a href="/mypage">Mypage</a></li>
                            @if (Auth::user()->role_id == 1)
                            <li class="admin-menu"><a href="/admin/register_shop_owner">administration page</a></li>
                            @endif
                            @if (Auth::user()->role_id == 2)
                            <li class="admin-menu"><a href="/admin/register_shop_data">administration page</a></li>
                            @endif
                        </ul>
                    </div>
                    @else
                    <div class="modal__list">
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li><a href="/register">Registration</a></li>
                            <li><a href="/login">Login</a></li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            @yield('content')

        </div>
    </main>

    <script src="{{ asset('js/util.js') }}"></script>
    @yield('script')
</body>

</html>
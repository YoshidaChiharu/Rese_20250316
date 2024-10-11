@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/reservation_detail.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="heading">
        <p class="heading__shop-name">{{ $shop->name }}</p>
    </div>
    <div class="content">
        <div class="content__heading">
            <span>●&nbsp;2024/10/11&nbsp;:&nbsp;予約1</span>
        </div>
        <table class="content__table">
            <tr>
                <th>お名前</th>
                <td>{{ $reservation->user->name }}</td>
            </tr>
            <tr>
                <th>予約日</th>
                <td>{{ $reservation->scheduled_on }}</td>
            </tr>
            <tr>
                <th>予約時刻</th>
                <td>{{ $reservation->start_at }} ～ {{ $reservation->finish_at }}</td>
            </tr>
            <tr>
                <th>予約人数</th>
                <td>{{ $reservation->number }} 名</td>
            </tr>
            <tr>
                <th>コース</th>
                <td>120分飲み放題コース</td>
            </tr>
            <tr>
                <th>事前決済</th>
                <td>なし</td>
            </tr>
            <tr>
                <th>ステータス</th>
                <td>来店前</td>
            </tr>
            <tr>
                <th>操作</th>
                <td>
                    <form action="/admin/reservation_list/{{ $shop->id }}/detail/{{ $reservation->id }}/edit" method="get">
                        <button class="form__button form__button--edit">編集</button>
                        <button class="form__button form__button--visit">来店処理</button>
                        <button class="form__button form__button--cancel">予約キャンセル</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
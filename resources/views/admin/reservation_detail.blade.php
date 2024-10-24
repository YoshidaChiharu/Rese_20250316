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
                @if($reservation->course_id)
                <td>{{ $reservation->course->name }}</td>
                @else
                <td>-</td>
                @endif
            </tr>
            <tr>
                <th>事前決済</th>
                <td>
                    @if($reservation->prepayment === 0)なし
                    @elseif($reservation->prepayment === 1)決済前
                    @elseif($reservation->prepayment === 2)決済完了
                    @elseif($reservation->prepayment === 3)返金済み
                    @endif
                </td>
            </tr>
            <tr>
                <th>ステータス</th>
                <td>
                    @if($reservation->status === 0)来店前
                    @elseif($reservation->status === 1)来店済み
                    @elseif($reservation->status === 2)予約キャンセル
                    @endif
                </td>
            </tr>
            <tr>
                <th>操作</th>
                <td>
                    <div class="operate-buttons">
                        <form action="/admin/reservation_list/{{ $shop->id }}/detail/{{ $reservation->id }}/edit" method="get">
                            @csrf
                            <button class="form__button form__button--edit">編集</button>
                        </form>
                        <form action="/admin/reservation_list/{{ $shop->id }}/detail/{{ $reservation->id }}/visit" method="post">
                            @csrf
                            <button class="form__button form__button--visit">来店処理</button>
                        </form>
                        <form action="/admin/reservation_list/{{ $shop->id }}/detail/{{ $reservation->id }}/cancel" method="post">
                            @csrf
                            <button class="form__button form__button--cancel">予約キャンセル</button>
                        </form>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
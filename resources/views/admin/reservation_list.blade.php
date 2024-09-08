@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/reservation_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="heading-section">
        <p class="heading__shop-name">店舗名（仮）</p>
    </div>
    <div class="calendar-section">
        <form action="/reservation_list" method="get">
            @csrf
            <div class="section__title">予約カレンダー</div>
            <div class="section__body">
                <div class="calendar-top">
                    <div class="calendar-top__year-month">2024年9月</div>
                    <div class="calendar-top__button">
                        <button class="button__this-month">今月</button>
                        <button class="button__prev">&lt;</button>
                        <button class="button__next">&gt;</button>
                    </div>
                </div>
                <div class="calendar-content">
                    <table class="calendar-content__table">
                        <tr>
                            <th class="table__header sunday">日&nbsp;(Sun)</th>
                            <th class="table__header">月&nbsp;(Mon)</th>
                            <th class="table__header">火&nbsp;(Tue)</th>
                            <th class="table__header">水&nbsp;(Wed)</th>
                            <th class="table__header">木&nbsp;(Thu)</th>
                            <th class="table__header">金&nbsp;(Fri)</th>
                            <th class="table__header saturday">土&nbsp;(Sat)</th>
                        </tr>
                        @for($i=0; $i<5; $i++)
                        <tr>
                            @for($j=0; $j<7; $j++)
                            <td class="table__data">
                                <p>{{ $i * 7 + $j }}</p>
                                <button>予約：1組2名</button>
                            </td>
                            @endfor
                        </tr>
                        @endfor
                    </table>
                </div>
            </div>
        </form>
    </div>
    <div class="time-schedule-section">
        <div class="section__title">タイムスケジュール</div>
        <div class="section__body">
            <div class="time-schedule-top">2024年9月7日&nbsp;:&nbsp;7組10名様</div>
            <div class="time-schedule-content">
                <table class="time-schedule-content__table">
                    <tr class="table__header">
                        <th class="scroll-rock">
                            <div class="columns"></div>
                        </th>
                        @for($i=12; $i<=24; $i++)
                        <td colspan="2" class="table__data">
                            <div class="columns">{{ $i }}:00</div>
                        </td>
                        @endfor
                    </tr>
                    <tr>
                        <th class="scroll-rock">予約１</th>
                        @for($i=12; $i<=24; $i++)
                        <td class="table__data"></td>
                        <td class="table__data"></td>
                        @endfor
                    </tr>
                    <tr>
                        <th class="scroll-rock">予約２</th>
                        @for($i=12; $i<=24; $i++)
                        <td class="table__data"></td>
                        <td class="table__data"></td>
                        @endfor
                    </tr>
                    <tr>
                        <th class="scroll-rock">予約３</th>
                        @for($i=12; $i<=24; $i++)
                        <td class="table__data"></td>
                        <td class="table__data"></td>
                        @endfor
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/reservation_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="heading-section">
        <p class="heading__shop-name">{{ $shops[request()->shop_index]->name }}</p>
    </div>
    <div class="calendar-section">
        <div class="section__title">［予約カレンダー］</div>
        <div class="section__body">
            <div class="calendar-top">
                <div class="calendar-top__year-month">{{ $this_year }}年{{ $this_month }}月</div>
                <div class="calendar-top__button">
                    <a class="button__this-month" href="/admin/reservation_list/{{ request()->shop_index }}">今月</a>
                    <form action="/admin/reservation_list/{{ request()->shop_index }}" method="get">
                        <input type="hidden" value="{{ $prev_year }}" name="year">
                        <input type="hidden" value="{{ $prev_month }}" name="month">
                        <button class="button__prev">&lt;</button>
                    </form>
                    <form action="/admin/reservation_list/{{ request()->shop_index }}" method="get">
                        <input type="hidden" value="{{ $next_year }}" name="year">
                        <input type="hidden" value="{{ $next_month }}" name="month">
                        <button class="button__next">&gt;</button>
                    </form>
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

                        @php
                            $index = $i * 7 + $j;
                            $year = $calendar[$index]['year'];
                            $month = $calendar[$index]['month'];
                            $day = $calendar[$index]['day'];
                            $group_num = $calendar[$index]['reservation_group_num'];
                            $people_num = $calendar[$index]['reservation_people_num'];
                        @endphp

                        <td @class([
                            'table__data',
                            'table__data--gray' => $calendar[$index]['is_past'],
                            'table__data--yellow' => $calendar[$index]['is_today'],
                        ])>
                            <p class="table__days">{{ $day }}</p>
                            @if($people_num)
                            <form action="/admin/reservation_list/{{ request()->shop_index }}" method="get">
                                <input type="hidden" value="{{ $this_year }}" name="year">
                                <input type="hidden" value="{{ $this_month }}" name="month">
                                <input type="hidden" value="{{ $year }}" name="time_schedule_year">
                                <input type="hidden" value="{{ $month }}" name="time_schedule_month">
                                <input type="hidden" value="{{ $day }}" name="time_schedule_day">
                                <button name="time_schedule" @class([
                                    'table__button',
                                    'table__button--blue',
                                    'table__button--gray' => $calendar[$index]['is_past'],
                                ])>
                                    予約：{{ $group_num }}組{{ $people_num }}名
                                </button>
                            </form>
                            @else
                            <div class="table__button">
                                予約：{{ $group_num }}組{{ $people_num }}名
                            </div>
                            @endif
                        </td>
                        @endfor
                    </tr>
                    @endfor
                </table>
            </div>
        </div>
    </div>
    <div class="time-schedule-section">
        <div class="section__title">［タイムスケジュール］</div>

        @if($time_schedule)
        <div class="section__body">

            @php
                $year = $time_schedule['year'];
                $month = $time_schedule['month'];
                $day = $time_schedule['day'];
                $group_num = $time_schedule['group_num'];
                $people_num = $time_schedule['people_num'];
                $reservations = $time_schedule['reservations'];
            @endphp

            <div class="time-schedule-top">
                {{ $year }}年{{ $month }}月{{ $day }}日&nbsp;:&nbsp;{{ $group_num }}組{{ $people_num }}名様
            </div>
            <div class="time-schedule-content">
                <table class="time-schedule-content__table">
                    <tr class="table__header">
                        <th class="scroll-rock"></th>
                        @for($i=12; $i<=24; $i++)
                        <td colspan="2" class="table__data">
                            <div class="columns">{{ $i }}:00</div>
                        </td>
                        @endfor
                    </tr>
                    @foreach($reservations as $reservation)
                    <tr>
                        <th class="scroll-rock">予約{{ $loop->iteration }}</th>
                        @for($i=12; $i<=24; $i++)
                            <!-- 「**:00 ～」の予約の場合 -->
                            @if($i.":00:00" == $reservation->start_at)
                            <td class="table__data relative">
                                <div class="reservation-box" length_minutes={{ $reservation->getMinutesLength() }} interval_minutes=30>
                                    {{ $reservation->user->name }}様&emsp;{{ $reservation->number }}名&emsp;{{ $reservation->start_at }}～{{ $reservation->finish_at }}
                                </div>
                            </td>
                            @else
                            <td class="table__data"></td>
                            @endif
                            <!-- 「**:30 ～」の予約の場合 -->
                            @if($i.":30:00" == $reservation->start_at)
                            <td class="table__data relative">
                                <div class="reservation-box" length_minutes={{ $reservation->getMinutesLength() }} interval_minutes=30>
                                    {{ $reservation->user->name }}様&emsp;{{ $reservation->number }}名&emsp;{{ $reservation->start_at }}～{{ $reservation->finish_at }}
                                </div>
                            </td>
                            @else
                            <td class="table__data"></td>
                            @endif
                        @endfor
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif

    </div>
</div>

<script src="{{ asset('js/reservation_list.js') }}"></script>
@endsection
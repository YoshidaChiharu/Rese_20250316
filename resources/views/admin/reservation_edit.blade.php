@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/reservation_edit.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="heading">
        <p class="heading__shop-name">{{ $shop->name }}</p>
    </div>
    <div class="reservation-edit-content">
        <div class="content__heading">
            <span>●&nbsp;2024/10/11&nbsp;:&nbsp;予約1</span>
        </div>
        <form class="content__form" action="/admin/reservation_list/{{ $shop->id }}/detail/{{ $reservation->id }}/edit" method="post">
            @csrf
            <table class="form__table">
                <tr>
                    <th>お名前</th>
                    <td>{{ $reservation->user->name }}</td>
                </tr>
                <tr>
                    <th>予約日</th>
                    <td>
                        <input type="date" name="reserve_date" class="form__input--date" value="{{ old('reserve_date') ?? $reservation->scheduled_on }}">
                        <div class="form-table__error">
                            @error('reserve_date')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>予約時刻</th>
                    <td>
                        <select name="reserve_time" class="form__input--select">
                            <option value="">-</option>
                            @foreach ($reservable_times as $reservable_time)
                            <option value="{{ $reservable_time }}" {{ old('reserve_time') ?? substr($reservation->start_at, 0, 5)  == $reservable_time ? 'selected' : '' }}>
                                {{ $reservable_time }}
                            </option>
                            @endforeach
                        </select>
                        <span>&nbsp;～&nbsp;</span>
                        <div class="form-table__error">
                            @error('reserve_time')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>予約人数</th>
                    <td>
                        @if($reservation->prepayment < 2)
                        <input type="text" name="reserve_number" class="form__input--text" value="{{ old('reserve_number') ?? $reservation->number }}" size="2"> 名
                        <div class="form-table__error">
                            @error('reserve_number')
                            ※{{ $message }}
                            @enderror
                        </div>
                        @else
                        {{ $reservation->number }} 名&emsp;※決済済み変更不可
                        <input type="hidden" name="reserve_number" value="{{ $reservation->number }}">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>コース</th>
                    <td>
                        @if($reservation->prepayment < 2)
                        <select name="reserve_course_id" class="form__input--select">
                            <option value="">-</option>
                            @foreach($shop->courses as $course)
                            <option value="{{ $course->id }}" {{ old('reserve_course_id') ?? $reservation->course_id  == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-table__error">
                            @error('reserve_course_id')
                            ※{{ $message }}
                            @enderror
                        </div>
                        @else
                        {{ $reservation->course->name }}&emsp;※決済済み変更不可
                        <input type="hidden" name="reserve_course_id" value="{{ $reservation->course_id }}">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>事前決済</th>
                    <td>
                        @if($reservation->prepayment < 2)
                        <select name="reserve_prepayment" class="form__input--select">
                            <option value="0">なし</option>
                            @if($shop->prepayment_enabled == 1)
                            <option value="1" {{ old('reserve_prepayment') ?? $reservation->prepayment  == 1 ? 'selected' : '' }}>
                                決済前
                            </option>
                            @endif
                        </select>
                        @elseif($reservation->prepayment == 2)決済完了
                        <input type="hidden" name="reserve_prepayment" value="2">
                        @elseif($reservation->prepayment == 3)返金済み
                        <input type="hidden" name="reserve_prepayment" value="3">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>ステータス</th>
                    <td>
                        <select name="reserve_status" class="form__input--select">
                            <option value="0">来店前</option>
                            <option value="1" {{ old('reserve_status') ?? $reservation->status  == 1 ? 'selected' : '' }}>来店済み</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>操作</th>
                    <td>
                        <button class="form__button">更新する</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
@endsection
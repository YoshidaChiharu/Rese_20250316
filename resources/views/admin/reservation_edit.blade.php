@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/reservation_edit.css') }}">
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
        <form class="content__form" action="/admin/reservation_list/{{ $shop->id }}/detail/{{ $reservation->id }}/edit" method="post">
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
                        <select name="reserve_start_at" class="form__input--select">
                            <option value="">-</option>
                            @foreach ($reservable_times as $reservable_time)
                            <option value="{{ $reservable_time }}" {{ old('reserve_time') ?? substr($reservation->start_at, 0, 5)  == $reservable_time ? 'selected' : '' }}>
                                {{ $reservable_time }}
                            </option>
                            @endforeach
                        </select>
                        <span>&nbsp;～&nbsp;</span>
                        <div class="form-table__error">
                            @error('reserve_start_at')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>予約人数</th>
                    <td>
                        <input type="text" name="reserve_number" class="form__input--text" value="{{ old('reserve_number') ?? $reservation->number }}" size="2"> 名
                        <div class="form-table__error">
                            @error('reserve_number')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>コース</th>
                    <td>
                        <select name="reserve_course" class="form__input--select">
                            <option value="">-</option>
                            <option value="1">60分飲み放題コース</option>
                            <option value="2">90分飲み放題コース</option>
                            <option value="3">120分飲み放題コース</option>
                        </select>
                        <div class="form-table__error">
                            @error('reserve_course')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
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
                        <button class="form__button">更新する</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
@endsection
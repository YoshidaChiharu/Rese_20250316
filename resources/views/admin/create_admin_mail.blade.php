@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/create_admin_mail.css') }}">
@endsection

@section('content')
<div class="admin-mail-wrapper">
    @if(session('is_sent') === true)
    <div class="message is_sent--true">{{ session('message') }}</div>
    @elseif(session('is_sent') === false)
    <div class="message is_sent--false">{{ session('message') }}</div>
    @endif
    <div class="admin-mail-content">
        <div class="admin-mail-content__heading">
            <h2>お知らせメール</h2>
        </div>
        <form action="/admin/admin_mail" class="admin-mail-content__form" method="post">
            @csrf
            <table class="form-table">
                <tr>
                    <th>件名</th>
                    <td>
                        <input class="form-table__input--text" type="text" value="{{ old('name') }}" name="subject">
                        <div class="form-table__error">
                            @error('subject')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>本文</th>
                    <td>
                        <textarea class="form-table__input--text" rows="20" name="main_text">{{ old('detail') }}</textarea>
                        <div class="form-table__error">
                            @error('main_text')
                            ※{{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
            </table>

            <div class="form__button">
                <button class="form__button-submit">送信</button>
            </div>
        </form>
    </div>
</div>
@endsection
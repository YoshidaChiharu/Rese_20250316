@extends('admin.app_admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/register_shop_owner.css') }}">
@endsection

@section('content')
<div class="register-wrapper">
    @if(session('result') === true)
    <div class="message result--true">{{ session('message') }}</div>
    @elseif(session('result') === false)
    <div class="message result--false">{{ session('message') }}</div>
    @endif
    <div class="register-content">
        <div class="register-content__heading">
            <h2>Registration&ensp;&lt;Shop owner&gt;</h2>
        </div>
        <form action="/admin/register_shop_owner" class="register-content__form" method="post">
            @csrf
            <input type="hidden" name="role_id" value="3">
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
            <div class="form__group">
                <div class="form__group--icon">
                    <img src="{{ asset('img/user.svg') }}" alt="user icon">
                </div>
                <div class="form__group--input">
                    <input type="text" class="form__group-input" name="name" placeholder="Username" value="{{ old('name') }}">
                </div>
            </div>
            <div class="form__error">
                @error('email')
                {{ $message }}
                @enderror
            </div>
            <div class="form__group">
                <div class="form__group--icon">
                    <img src="{{ asset('img/mail.svg') }}" alt="mail icon">
                </div>
                <div class="form__group--input">
                    <input type="text" class="form__group-input" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="form__error">
                @error('password')
                {{ $message }}
                @enderror
            </div>
            <div class="form__group">
                <div class="form__group--icon">
                    <img src="{{ asset('img/password.svg') }}" alt="password icon">
                </div>
                <div class="form__group--input">
                    <input type="text" class="form__group-input" name="password" placeholder="Password" value="{{ old('password') }}">
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" id="js-submit-button">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection
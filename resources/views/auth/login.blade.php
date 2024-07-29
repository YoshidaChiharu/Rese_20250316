@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-content">
        <div class="login-content__heading">
            <h2>Login</h2>
        </div>
        <form action="/login" class="login-content__form" method="post">
            @csrf
            <div class="form__group">
                <div class="form__group--icon">
                    <img src="{{ asset('img/mail.svg') }}" alt="mail icon">
                </div>
                <div class="form__group--input">
                    <input type="text" class="form__group-input" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
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
                <button class="form__button-submit">ログイン</button>
            </div>
        </form>
    </div>
</div>
@endsection
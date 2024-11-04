@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/reservation_qr.css') }}">
@endsection

@section('content')
<div class="qr-wrapper">
    <div class="qr-text">
        <p>
            店舗側がお客様の予約情報を照会する為に使用するQRコードです<br>
            ご来店の際、店舗にて以下QRコードをご提示下さい<br>
        </p>
    </div>
    <div class="qr-image">
        <img src="data:image/png;base64, {!! $qr_code !!} ">
    </div>
</div>
@endsection
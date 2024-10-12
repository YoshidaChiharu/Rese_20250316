<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご予約完了メール</title>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <p>{{ $reservation->user->name }}&nbsp;様</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    この度はRese予約サービスをご利用いただき、誠にありがとうございます。<br>
                    下記の内容にてご予約を承りましたのでご確認ください。
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    ■店舗名<br>
                    {{ $reservation->shop->name }}
                </p>
                <p>
                    ■予約日時<br>
                    {{ $reservation->scheduled_on }}&emsp;{{ substr($reservation->start_at, 0, 5) }}～
                </p>
                <p>
                    ■予約人数<br>
                    {{ $reservation->number }}名様<br>
                </p>
                <p>
                    ■コース<br>
                    （仮）120分飲み放題コース<br>
                </p>
                <p>
                    ■お支払い予定金額<br>
                    （仮）&yen;6,800 x {{ $reservation->number }}名様分<br>
                    （仮）【合計】&yen;13,600（税込）<br>
                </p>
                <p>
                    ■お支払い方法<br>
                    （仮）店舗でのお支払い<br>
                </p>
                <p>
                    ■予約QRコード<br>
                    店舗側がお客様の予約情報を照会する為に使用するQRコードです<br>
                    ご来店の際、店舗にて以下QRコードをご提示下さい<br>
                </p>
                <img src="data:image/png;base64, {!! $qr_code !!} ">
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    ※予約確認及びキャンセルについては [Mypage] よりお願い致します<br>
                    ※本メールは送信専用です<br>
                    ------------------------------------------------------------------------------------------------------------<br>
                    配信元&nbsp;:&nbsp;Rese
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
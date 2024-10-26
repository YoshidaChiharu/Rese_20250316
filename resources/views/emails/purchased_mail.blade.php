<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>決済完了メール</title>
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
                    下記ご予約の事前決済が完了しましたのでご確認ください。
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    ■ご予約内容<br>
                    店舗：{{ $reservation->shop->name }}<br>
                    ご予約コース：{{ $reservation->course->name }}<br>
                    コース代金：&yen;{{ $reservation->course->price }}<br>
                    ご予約人数：{{ $reservation->number }}名様<br>
                </p>
                <p>
                    ■お支払い金額<br>
                    &yen;{{ number_format($reservation->course->price) }} x {{ $reservation->number }}名様分<br>
                    【合計】&yen;{{ number_format($reservation->course->price * $reservation->number) }}（税込）<br>
                </p>
                <p>
                    ■決済方法<br>
                    クレジットカード決済：{{ $card_brand }}<br>
                    ****-****-****-{{ $card_last4 }}<br>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    ※予約キャンセル（返金処理）については [Mypage] よりお願い致します<br>
                    ※本メールは送信専用です<br>
                    ------------------------------------------------------------------------------------------------------------<br>
                    配信元&nbsp;:&nbsp;Rese
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
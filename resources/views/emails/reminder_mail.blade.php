<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご予約当日のお知らせ</title>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <p>{{ $name }}&nbsp;様</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    この度はRese予約サービスをご利用いただき、誠にありがとうございます。<br>
                    本日、以下店舗のご予約を承っております。<br>
                    予約内容についてご連絡させて頂きますので、ご確認をよろしくお願い致します。
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    --------------------<br>
                    ■店舗名<br>
                    {{ $reservation->shop->name }}
                </p>
                <p>
                    ■予約日時<br>
                    {{ $reservation->scheduled_on }}&emsp;{{ substr($reservation->start_at, 0, 5) }}～
                </p>
                <p>
                    ■予約人数<br>
                    {{ $reservation->number }}人<br>
                    --------------------
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    ※本メールと行き違いでキャンセル頂いております場合は、何卒ご容赦ください<br>
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
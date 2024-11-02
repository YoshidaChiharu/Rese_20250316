<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お知らせメール</title>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <p style="font-size: 18px; font-weight: bold;">{{ $name }}&nbsp;様</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>{!! nl2br(e( $main_text )) !!}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                ※本メールは送信専用です<br>
                ------------------------------------------------------------------------------------------------------------<br>
                配信元&nbsp;:&nbsp;Rese
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
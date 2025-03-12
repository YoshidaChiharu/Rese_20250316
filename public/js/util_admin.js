//********************************************************************
// フォーム送信ボタンが複数回連打されても一度しか送信されない様にする対策
//********************************************************************

/**
 * ボタン連打防止対策
 *
 * id="js-submit-button" が設定されたボタンに対し、
 * 一度ボタン押下したら非活性にして二度目以降の押下を無効化する処理を登録
 */
document.getElementById('js-submit-button').addEventListener('click', function () {
    console.log(document.getElementById('js-submit-button'));
    this.disabled = true;
    this.closest('form').submit();
});
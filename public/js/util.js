//********************************************************************
// フォーム入力値を別の場所に即座に表示する
//********************************************************************

/**
 * フォーム入力値を即時反映して表示
 * @param {number} value 表示したい値（フォーム入力値）
 * @param {string} id 表示先オブジェクトのid名
 */
function setValue(value, id) {
    document.getElementById(id).textContent = value;
}

//********************************************************************
// 「もっと見る」ボタン押下時に隠れていた部分を展開する為のアクション登録
//********************************************************************

/**
 * 「もっと見る」「閉じる」ボタン押下時のアクションを登録する
 * @param {Element} content ボタン押下時に展開する範囲
 * @param {Element} button 「もっと見る」ボタン
 */
function readMore(content, button) {
    const content_height = content.clientHeight;

    button.addEventListener('click', () => {
        if(!content.classList.contains('show')){
            content.style.maxHeight = content.scrollHeight + 'px';
            content.classList.add('show');
            button.innerText = '▲ 閉じる ▲';
        } else {
            content.style.maxHeight = content_height + 'px';
            content.classList.remove('show');
            button.innerText = '▼ もっと見る ▼';
        }
    });
}

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
    this.disabled = true;
    this.closest('form').submit();
});
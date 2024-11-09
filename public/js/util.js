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
// ページのスクロール位置を保存しておき、同ページが表示された際に復元する
//********************************************************************

/**
 * @type {number} scrollPosition ページスクロール位置
 * @type {string} STORAGE_KEY スクロール位置保存時のキー
 */
var scrollPosition;
const STORAGE_KEY = "scrollY";

/**
 * スクロール位置保存メソッド
 */
function saveScrollPosition(){
    scrollPosition = window.scrollY;
    localStorage.setItem(STORAGE_KEY, scrollPosition);
}

/**
 * ページがロードされた際に保存していたスクロール位置を復元
 */
window.addEventListener("load", function(){
    scrollPosition = localStorage.getItem(STORAGE_KEY);
    if(scrollPosition !== null){
        scrollTo(0, scrollPosition);
    }
    // ページスクロールされる度にスクロール位置を保存
    window.addEventListener("scroll", saveScrollPosition, false);
});

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
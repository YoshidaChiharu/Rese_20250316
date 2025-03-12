//********************************************************************
// ページのスクロール位置を保存 → 同ページが表示された際に復元
//
// ※ページネーション移動やお気に入り追加／削除などでスクロール位置が
//   ページ最上部に戻ってしまうのを防ぐ意図
//********************************************************************

/**
 * @type {number} scroll_position ページスクロール位置
 * @type {string} STORAGE_KEY スクロール位置保存時のキー
 */
var scroll_position;
const STORAGE_KEY = "shop_all_scroll_position";

console.log(STORAGE_KEY, scroll_position);

/**
 * スクロール位置保存メソッド
 */
function saveScrollPosition(){
    scroll_position = window.scrollY;
    sessionStorage.setItem(STORAGE_KEY, scroll_position);
}

/**
 * ページがロードされた際に保存していたスクロール位置を復元
 */
window.addEventListener("load", function () {
    scroll_position = sessionStorage.getItem(STORAGE_KEY);
    if(scroll_position !== null){
        scrollTo(0, scroll_position);
    }
    // ページスクロールされる度にスクロール位置を保存
    window.addEventListener("scroll", saveScrollPosition, false);
});
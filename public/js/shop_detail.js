/**
 * 「もっと見る」ボタンで口コミ欄を開閉する為のアクション登録
 */
window.onload = function () {
    var reviews = document.getElementsByClassName('review-item');

    reviews = Array.from(reviews);
    reviews.forEach((review) => {
        const content = review.querySelector('.review-content__comment');
        const button = review.querySelector('.read-more-button');

        var max_height = window.getComputedStyle(content).getPropertyValue('max-height')
        max_height = max_height.replace(/[^0-9]/g, '');

        if (content.scrollHeight <= max_height) {
            content.classList.add('hidden');
            button.classList.add('hidden');
        }

        if (button == null) { return; }
        readMore(content, button);
    });
}

/**
 * 予約フォームで選択されたコースの名前、時間、金額を確認欄に表示
 * @param {string[][]} courses 全コース情報の配列
 * @param {number} course_id フォームで選択されたコースのID
 */
function setCourseValue(courses, course_id) {
    courses.forEach(function (course) {
        if (course['id'] == course_id) {
            document.getElementById('confirm_course').textContent = course['name'];
            document.getElementById('confirm_duration').textContent = course['duration_minutes'] + ' 分';
            document.getElementById('confirm_price').textContent = course['price'].toLocaleString() + ' 円 × 人数分';
        }
   });
}

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
const SHOP_ID = document.getElementById('js_shop_id').value;
const STORAGE_KEY = "shop_detail_" + SHOP_ID + "_scroll_position";

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
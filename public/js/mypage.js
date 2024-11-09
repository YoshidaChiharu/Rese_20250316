/**
 * 評価用★アイコンクリック時のアクション
 * @param {number} value 何番目の★がクリックされたかを表す数値
 * @returns false ★クリック時にフォーム送信されないよう常にfalseを返す
 */
function changeStar(value) {
    // 星画像の変更
    const stars = document.getElementsByClassName('review__input--star');

    for (let i = 0; i < stars.length; i++) {
        stars[i].setAttribute('src', "/img/star_off_gold.svg");
    }
    for (let i = 0; i < value; i++) {
        stars[i].setAttribute('src', "/img/star_on_gold.svg");
    }

    // フォーム送信用データの数値(rating)を変更
    const level = document.getElementById('rating');
    level.value = value;

    // フォーム送信をキャンセル
    return false;
}

/**
 * 「もっと見る」ボタンで口コミ欄を開閉する為のアクション登録
 */
window.onload = function () {
    var reviews = document.getElementsByClassName('reserve-card__review');

    reviews = Array.from(reviews);
    reviews.forEach((review) => {
        const content = review.querySelector('.review__comment');
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
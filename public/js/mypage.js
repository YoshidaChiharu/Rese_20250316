function changeStar(value) {
    // 星画像の変更
    const stars = document.getElementsByClassName('review__image--star');

    for (let i = 0; i < stars.length; i++) {
        // stars[i].setAttribute('src', "{{ asset('img/star_off_gold.svg') }}");
        stars[i].setAttribute('src', "../img/star_off_gold.svg");
    }
    for (let i = 0; i < value; i++) {
        // stars[i].setAttribute('src', "{{ asset('img/star_on_gold.svg') }}");
        stars[i].setAttribute('src', "../img/star_on_gold.svg");
    }

    // フォーム送信用データの数値(review_level)を変更
    const level = document.getElementById('review_level');
    level.value = value;

    // フォーム送信をキャンセル
    return false;
}
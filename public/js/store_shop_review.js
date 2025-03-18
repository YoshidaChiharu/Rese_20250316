//********************************************************************
// 評価用★アイコンクリック時のアクション
//********************************************************************

/**
 * ★アイコンクリックによる評価値変更処理
 * @param {number} value 何番目の★がクリックされたかを表す数値
 * @returns false ★クリック時にフォーム送信されないよう常にfalseを返す
 */
function changeStar(value) {
    // 星画像の変更
    const stars = document.getElementsByClassName('review__input--star');

    for (let i = 0; i < stars.length; i++) {
        stars[i].setAttribute('src', "/img/star_off_blue.svg");
    }
    for (let i = 0; i < value; i++) {
        stars[i].setAttribute('src', "/img/star_on_blue.svg");
    }

    // フォーム送信用データの数値(rating)を変更
    const level = document.getElementById('rating');
    level.value = value;

    // フォーム送信をキャンセル
    return false;
}

//********************************************************************
// 画像登録欄：画像をドラッグ＆ドロップで登録
//********************************************************************

let target = document.getElementById('drop-target');
let input = document.getElementById("input-file");

/**
 * 画像ドラッグ時
 */
target.addEventListener('dragover', function (e) {
	e.preventDefault();
	e.stopPropagation();
	e.dataTransfer.dropEffect = 'copy';
});

/**
 * 画像ドロップ時
 */
target.addEventListener('drop', function (e) {
	e.stopPropagation();
    e.preventDefault();

    // inputタグに画像ファイルをセット
    const files = e.dataTransfer.files;
    input.files = files;

    // 画像のプレビュー表示
    const reader = new FileReader();
    reader.onload = function (e) {
		document.getElementById('drop-area__preview').src = e.target.result;
		document.getElementById('drop-area__text').classList.add('hidden');
	}
	reader.readAsDataURL(files[0]);
});

/**
 * 画像変更時
 */
input.addEventListener('change', function (e) {
	const [file] = e.target.files;
	if (file) {
		document.getElementById('drop-area__preview').src = URL.createObjectURL(file);
		document.getElementById('drop-area__text').classList.add('hidden');
	}
});

//********************************************************************
// 口コミコメントの文字数表示
//********************************************************************

var comment = document.getElementById("input-comment");
var span = document.getElementById("comment-length");

window.onload = setCommentLength();

comment.addEventListener("keyup", function () {
    setCommentLength();
});

function setCommentLength() {
    span.textContent = comment.value.length + "/400（最高文字数）";
    if (comment.value.length > 400) {
        span.classList.add('comment-length--red');
    } else {
        span.classList.remove('comment-length--red');
    }
}
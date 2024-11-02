// フォーム入力値を即時反映して表示
function setValue(value, id) {
    document.getElementById(id).textContent = value;
}

// ページスクロール位置の保存と復元
var scrollPosition;
const STORAGE_KEY = "scrollY";

function saveScrollPosition(){
    scrollPosition = window.scrollY;
    localStorage.setItem(STORAGE_KEY, scrollPosition);
}

window.addEventListener("load", function(){
    scrollPosition = localStorage.getItem(STORAGE_KEY);
    if(scrollPosition !== null){
        scrollTo(0, scrollPosition);
    }
    window.addEventListener("scroll", saveScrollPosition, false);
});

// 「もっと見る」ボタン押下時のアクション
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
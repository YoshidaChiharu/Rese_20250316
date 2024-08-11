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
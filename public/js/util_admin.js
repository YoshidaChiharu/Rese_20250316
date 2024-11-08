// ボタン連打防止対策
document.getElementById('js-submit-button').addEventListener('click', function () {
    console.log(document.getElementById('js-submit-button'));
    this.disabled = true;
    this.closest('form').submit();
});
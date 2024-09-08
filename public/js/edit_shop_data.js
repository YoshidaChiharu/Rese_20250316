let target = document.getElementById('drop-target');

target.addEventListener('dragover', function (e) {
	e.preventDefault();
	e.stopPropagation();
	e.dataTransfer.dropEffect = 'copy';
});

target.addEventListener('drop', function (e) {
	e.stopPropagation();
    e.preventDefault();

    // inputタグに画像ファイルをセット
    const files = e.dataTransfer.files;
    document.getElementById("input-file").files = files;

    // 画像のプレビュー表示
    const reader = new FileReader();
    reader.onload = function (e) {
		document.getElementById('drop-area__preview').src = e.target.result;
		document.getElementById('drop-area__icon').classList.add('hidden');
		document.getElementById('drop-area__text').classList.add('hidden');
	}
	reader.readAsDataURL(files[0]);
});

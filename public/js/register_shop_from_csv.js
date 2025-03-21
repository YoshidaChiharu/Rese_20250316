//********************************************************************
// CSVファイルインポート
//********************************************************************

const inputCSV = document.getElementById('input-csv');
const previewCSV = document.getElementById('csv-preview');
const previewNeedImageName = document.getElementById('need-image-list');
var needImageNameList = [];

/**
 * csvファイル変更時
 */
inputCSV.addEventListener('change', function (e) {
    const reader = new FileReader();
    const file = e.target.files[0];
    const message = document.getElementById('csv-error-message');

    // エラーメッセージ初期化
    while (message.firstChild) {
        message.removeChild(message.firstChild)
    }
    // プレビュー表示の初期化
    previewCSV.textContent = null;
    // 必要な画像ファイル名表示の初期化
    while (previewNeedImageName.firstChild) {
        previewNeedImageName.removeChild(previewNeedImageName.firstChild)
    }
    // 選択済み画像ファイルのリセット
    resetImageFiles();

    // csv読み込み＆中身のプレビュー
    if (file.type === 'text/csv') {
        reader.onload = () => {
            // csv読み込み
            const data = reader.result.trim().split('\n').map((row) => row.trim().split(','));

            if (data[0].toString() === "owner_id,name,area,genre,detail,image") {
                // プレビュー表示
                previewCSV.textContent = reader.result;
                // 必要な画像ファイル名をプレビュー表示
                const FileList = [];
                for (i = 1; i < data.length; i++) {
                    if (data[i][5]) {
                        FileList.push(data[i][5]);
                    }
                }
                needImageNameList = Array.from(new Set(FileList));
                needImageNameList.sort();
                needImageNameList.forEach((imageName) => {
                    const name = document.createElement("li");
                    name.textContent = imageName;
                    previewNeedImageName.appendChild(name);
                });
            } else {
                // csvの中身が不正
                const errorMessage = document.createElement("div");
                errorMessage.classList.add('form-table__error');
                errorMessage.textContent = "CSVファイルの記述が不正です。以下カラムが正しく含まれているか確認して下さい。\n" + "owner_id,name,area,genre,detail,image";
                message.appendChild(errorMessage);

                inputCSV.value = null;
                needImageNameList = [];
            }
        }
        reader.readAsText(file);
    } else {
        // 不正なファイル
        const errorMessage = document.createElement("div");
        errorMessage.classList.add('form-table__error');
        errorMessage.textContent = "CSVファイルを選択して下さい";
        message.appendChild(errorMessage);

        inputCSV.value = null;
        needImageNameList = [];
    }
});

//********************************************************************
// 画像追加：画像ファイルを選択、またはドラッグ＆ドロップで追加
//********************************************************************

const dropTarget = document.getElementById('drop-target');
const inputImage = document.getElementById("input-image");
const previewInputImageName = document.getElementById('input-image-list');
const errorMessageSpan = document.getElementById('input-image-error');
var imageList = [];

/**
 * 画像ドラッグ時
 */
dropTarget.addEventListener('dragover', function (e) {
	e.preventDefault();
	e.stopPropagation();
	e.dataTransfer.dropEffect = 'copy';
});

/**
 * 画像ドロップ時
 */
dropTarget.addEventListener('drop', function (e) {
	e.stopPropagation();
    e.preventDefault();

    const files = e.dataTransfer.files;
    addImageFiles(files);
});

/**
 * 画像変更時
 */
inputImage.addEventListener('change', function (e) {
    const files = e.target.files;
    addImageFiles(files);
});

/**
 * 画像ファイル追加メソッド
 * @param {FileList} files 読み込んだ画像ファイル
 */
function addImageFiles(files) {
    const dt = new DataTransfer();
    const inputImageNameList = [];

    imageList.forEach((image) => {
        dt.items.add(image);
    });

    for (i = 0; i < files.length; i++) {
        if (!imageList.some(image => image.name === files[i].name)) {
            dt.items.add(files[i]);
            imageList.push(files[i]);
        }
    };

    inputImage.files = dt.files;

    // 画像ファイル名表示
    while(previewInputImageName.firstChild){
        previewInputImageName.removeChild(previewInputImageName.firstChild)
    }
    imageList.forEach((image) => {
        // ファイル名のみの配列を作成
        inputImageNameList.push(image.name);
    });
    inputImageNameList.sort();
    inputImageNameList.forEach((inputImageName) => {
        const name = document.createElement("li");
        name.textContent = inputImageName;
        previewInputImageName.appendChild(name);
    });

    const diff = needImageNameList.filter(needImage =>
        inputImageNameList.indexOf(needImage) == -1
    );
    if (diff.length === 0) {
        dropTarget.classList.remove('image-name-preview--false');
        errorMessageSpan.classList.add('hidden');
    } else {
        dropTarget.classList.add('image-name-preview--false');
        errorMessageSpan.classList.remove('hidden');
    }
}

/**
 * 画像リセットメソッド
 */
function resetImageFiles() {
    // 選択ファイルのリセット
    inputImage.value = null;
    imageList = [];
    // 選択したファイル名の表示をリセット
    while(previewInputImageName.firstChild){
        previewInputImageName.removeChild(previewInputImageName.firstChild)
    }
    // エラー表示のリセット
    dropTarget.classList.remove('image-name-preview--false');
    errorMessageSpan.classList.add('hidden');
}
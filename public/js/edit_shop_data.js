let target = document.getElementById('drop-target');
let input = document.getElementById("input-file");

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
    input.files = files;

    // 画像のプレビュー表示
    const reader = new FileReader();
    reader.onload = function (e) {
		document.getElementById('drop-area__preview').src = e.target.result;
		document.getElementById('drop-area__icon').classList.add('hidden');
		document.getElementById('drop-area__text').classList.add('hidden');
	}
	reader.readAsDataURL(files[0]);
});

input.addEventListener('change', function (e) {
	const [file] = e.target.files;
	if (file) {
		document.getElementById('drop-area__preview').src = URL.createObjectURL(file);
		document.getElementById('drop-area__icon').classList.add('hidden');
		document.getElementById('drop-area__text').classList.add('hidden');
	}
});

let add_course_target = document.getElementById('js-add-course-target');

// コース入力欄の追加
function addCourse() {
	let child_count = add_course_target.childElementCount;
	console.log(child_count);

	let li = document.createElement('LI');
	li.classList.add('course-list-item');

	// マイナスボタン
	var a = document.createElement('A');
    a.classList.add('course-button');
	a.setAttribute('onclick', 'deleteCourse(this)');
	a.setAttribute('href', 'javascript:void(0)');
	
	var img = document.createElement('IMG');
	img.setAttribute('src', '/img/minus.svg');
	a.appendChild(img);

	li.appendChild(a);
	
	// コース名入力欄
	var input = document.createElement('INPUT');
    input.classList.add('course-list-item__input');
    input.setAttribute('placeholder', 'コース名');
    input.setAttribute('name', 'courses[' + child_count + '][name]');
	li.appendChild(input);

	// 時間入力欄
	var input = document.createElement('INPUT');
    input.classList.add('course-list-item__input');
    input.setAttribute('type', 'number');
    input.setAttribute('min', '60');
    input.setAttribute('max', '180');
    input.setAttribute('step', '30');
    input.setAttribute('value', '60');
    input.setAttribute('name', 'courses[' + child_count + '][duration_minutes]');
	li.appendChild(input);

	var span = document.createElement('SPAN');
	span.textContent = "分";
	li.appendChild(span);

	// 金額入力欄
	var input = document.createElement('INPUT');
    input.classList.add('course-list-item__input');
    input.setAttribute('type', 'number');
    input.setAttribute('min', '0');
    input.setAttribute('step', '1');
    input.setAttribute('value', '100');
    input.setAttribute('name', 'courses[' + child_count + '][price]');
	li.appendChild(input);

	var span = document.createElement('SPAN');
	span.textContent = "円";
	li.appendChild(span);

	add_course_target.appendChild(li);
}

// コース入力欄の削除
function deleteCourse(obj) {
	let target = obj.closest('.course-list-item');
	console.log(target);
	target.remove();
}
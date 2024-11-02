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

// 選択されたコースの名前、時間、金額を表示
function setCourseValue(courses, course_id) {
    courses.forEach(function (course) {
        if (course['id'] == course_id) {
            document.getElementById('confirm_course').textContent = course['name'];
            document.getElementById('confirm_duration').textContent = course['duration_minutes'] + ' 分';
            document.getElementById('confirm_price').textContent = course['price'].toLocaleString() + ' 円 × 人数分';
        }
   });
}
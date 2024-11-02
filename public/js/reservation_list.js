// 予約時間に応じて黄色BOXの幅を決定
window.addEventListener("load", function () {
    const reservations = document.getElementsByClassName('reservation-box');

    if (reservations) {
        const cell_width = reservations[0].closest('td').getBoundingClientRect().width;

        for (var reservation of reservations) {
            const length = reservation.getAttribute('length_minutes');      // 予約時間の長さ(min)
            const interval = reservation.getAttribute('interval_minutes');  // テーブル1マスの時間(min)

            const box_width = cell_width * (length / interval)
            reservation.style.width = box_width + 'px';
        }
    }
});
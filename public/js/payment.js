// 基本設定
const stripe_key_element = document.getElementById('js-stripe-key');
const stripe_key = stripe_key_element.getAttribute('stripe_key');
const stripe = Stripe(stripe_key);
const elements = stripe.elements();

// カード番号
var cardNumber = elements.create('cardNumber');
cardNumber.mount('#card-number');
cardNumber.on('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// カード有効期限
var cardExpiry = elements.create('cardExpiry');
cardExpiry.mount('#card-expiry');
cardExpiry.on('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// セキュリティーコード
var cardCvc = elements.create('cardCvc');
cardCvc.mount('#card-cvc');
cardCvc.on('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// PaymentMethodIDを取得してフォーム送信
var form = document.getElementById('card-form');
form.addEventListener('submit', async function (event) {
    // submitイベントをキャンセル
    event.preventDefault();

    // 二重押下防止のためボタン非活性
    document.getElementById('card-button').disabled = true;

    var errorElement = document.getElementById('card-errors');
    if (event.error) {
        // エラーメッセージを表示
        errorElement.textContent = event.error.message;
        // ボタン活性
        document.getElementById('card-button').disabled = false;
    } else {
        errorElement.textContent = '';
    }

    const result = await stripe.createPaymentMethod({
        type: 'card',
        card: cardNumber, // カード情報を渡す
    });

    if (result.error) {
        // エラーメッセージを表示
        errorElement.textContent = result.error.message;
        // ボタン活性
        document.getElementById('card-button').disabled = false;
    } else {
        // PaymentMethod IDをサーバーに送信
        stripePaymentIdHandler(result.paymentMethod.id, result.paymentMethod.card?.brand, result.paymentMethod.card?.last4);
    }
});

// paymentMethodId送信メソッド
function stripePaymentIdHandler(paymentMethodId, cardBrand, cardLast4) {
    const form = document.getElementById('card-form');

    // paymentMethodId
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'paymentMethodId');
    hiddenInput.setAttribute('value', paymentMethodId);
    form.appendChild(hiddenInput);

    // カードブランド
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'cardBrand');
    hiddenInput.setAttribute('value', cardBrand);
    form.appendChild(hiddenInput);

    // カード番号下4桁
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'cardLast4');
    hiddenInput.setAttribute('value', cardLast4);
    form.appendChild(hiddenInput);

    // フォーム送信
    form.submit();
}
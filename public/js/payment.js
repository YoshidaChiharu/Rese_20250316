/* 基本設定*/
const stripe_key = "pk_test_51QBad1Bli9nlS8GV0wskk4eK8OoTM6vLGUuQ7igRELuoB3B4YlbN4ubnUKFPaFeXeTqju80TN1vyXrMS7LWFY4zb00Pd2mQYT5"
const stripe = Stripe(stripe_key);
const elements = stripe.elements();
console.log(stripe_key);

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

var form = document.getElementById('card-form');
form.addEventListener('submit', async function(event) {
    event.preventDefault();
    var errorElement = document.getElementById('card-errors');
    if (event.error) {
        errorElement.textContent = event.error.message;
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
    } else {
        // PaymentMethod IDをサーバーに送信
        console.log(result.paymentMethod.id);
        console.log(result.paymentMethod.card?.brand);
        console.log(result.paymentMethod.card?.last4);
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


// ▼ paymentMethodを使用した実装 =========================================
//
// const stripe_key = "{{ config('stripe.stripe_key') }}"
// const stripe = Stripe(stripe_key);
// // const elements = stripe.elements();
// // const cardElement = elements.create('card', {
// //     hidePostalCode: true,
// //     hideIcon: true
// // });

// const options = {
//     mode: 'payment',
//     amount: 100,
//     currency: 'jpy',
//     paymentMethodCreation: 'manual',
//     appearance: {
//         theme: 'stripe'
//     },
// };
// const elements = stripe.elements(options);
// const cardElement = elements.create('payment');

// cardElement.mount('#card-element');

// const cardHolderName = document.getElementById('card-holder-name');
// const cardButton = document.getElementById('card-button');
// const form = document.getElementById('card-form');

// cardButton.addEventListener('click', async (e) => {
//     e.preventDefault()
//     // const { paymentMethod, error } = await stripe.createPaymentMethod(
//     //     'card', cardElement, {
//     //         billing_details: { name: cardHolderName.value }
//     //     }
//     // );

//     // Trigger form validation and wallet collection
//     const {error: submitError} = await elements.submit();
//     if (submitError) {
//         console.log(submitError);
//         return;
//     }

//     const {error, paymentMethod} = await stripe.createPaymentMethod({
//         elements,
//         params: {
//             billing_details: {
//                 name: cardHolderName.value,
//             }
//         }
//     });

//     if (error) {
//         // "error.message"をユーザーに表示…
//         console.log(error);
//     } else {
//         // カードは正常に検証された…
//         stripePaymentIdHandler(paymentMethod.id);
//     }
// });

// function stripePaymentIdHandler(paymentMethodId) {
//     // フォームに paymentMethodId を挿入してサーバーに送信
//     const form = document.getElementById('card-form');

//     const hiddenInput = document.createElement('input');
//     hiddenInput.setAttribute('type', 'hidden');
//     hiddenInput.setAttribute('name', 'paymentMethodId');
//     hiddenInput.setAttribute('value', paymentMethodId);
//     form.appendChild(hiddenInput);
//     form.submit();
// }
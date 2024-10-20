<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // 決済ページ表示 ===============================================================
    public function showPayment(Request $request) {
        return view('payment');
    }
}

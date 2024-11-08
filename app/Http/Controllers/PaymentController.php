<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use App\Jobs\SendPurchasedMail;

class PaymentController extends Controller
{
    // 決済ページ表示 ===============================================================
    public function create(Request $request) {
        $reservation = Reservation::find($request->reservation_id);

        // prepaymentフラグが「1：決済前」以外はホームへリダイレクト(決済済みページの再表示を防止)
        if($reservation->prepayment != 1) {
            return redirect('/');
        }

        return view('payment', compact('reservation'));
    }

    // 決済処理 ====================================================================
    public function store(Request $request) {
        // 二重送信防止
        $request->session()->regenerateToken();

        try {
            DB::beginTransaction();

            // 予約情報と価格の取得
            $reservation = Reservation::find($request->reservation_id);
            $course_price = $reservation->course->price;
            $number = $reservation->number;
            $total_price = $course_price * $number;

            // Stripe決済処理
            $payment_intent = $request->user()->charge($total_price, $request->paymentMethodId,[
                'return_url' => route('payment.completed'),
            ]);

            // 予約情報の更新
            $reservation->update([
                'prepayment' => 2,
                'payment_intent_id' => $payment_intent->id,
            ]);

            // 予約完了メールの送信
            SendPurchasedMail::dispatch($reservation, $request->cardBrand, $request->cardLast4);

            DB::commit();
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
        }

        return redirect('/purchase_completed');

    }

    // 決済完了ページ表示 ===========================================================
    public function completed(Request $request) {
        return view('purchase_completed');
    }
}

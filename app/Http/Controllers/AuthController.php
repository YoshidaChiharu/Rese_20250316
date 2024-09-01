<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Models\User;
use Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Lang;
use Mail;
use App\Mail\LoginMail;

class AuthController extends AuthenticatedSessionController
{
    // 登録完了ページ表示 ==================================================================
    public function showThanksRegister () {
        return view('auth.thanks_register');
    }

    // メール送信済みページ表示 ============================================================
    public function showMailAnnounce()
    {
        return view('auth.mail_announce');
    }

    // 認証エラーページ表示 ================================================================
    public function showAuthError(Request $request)
    {
        return view('auth.auth_error');
    }

    // 認証処理：第1段階目(メルアド、パスワードでの認証) =====================================
    public function authFirst(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
            return redirect('/login')
                ->with('message', Lang::get('message.ERR_USER_NOT_FOUND'));
        }

        if (Hash::check($request->password, $user->password)) {
            // token発行と登録
            $token = str::uuid();
            $expire_at = Carbon::now()->addMinute(10)->format('Y-m-d H:i:s');
            $user->update([
                'token' => $token,
                'expire_at' => $expire_at,
            ]);

            // メール送信
            $url = request()->getSchemeAndHttpHost() . "/auth_second?email=" . $user->email . "&token=" . $token;
            Mail::to($user->email)->send(new LoginMail($url));

            // メール送信済みページへリダイレクト
            return redirect('/auth_first')->with(['url' => $user->email]);
        } else {
            return redirect('/login')
                ->with('message', Lang::get('message.ERR_PASSWORD_MISMATCH'));
        }
    }

    // 認証処理：第2段階目(メール送付URLでの認証) ==========================================
    public function authSecond(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (
            $request->filled('token') &&
            $request->token === $user->token
        ) {
            // tokenの有効期限切れチェック
            $now = Carbon::now();
            $expire = new Carbon($user->expire_at);
            if ($expire <= $now) {
                $user->update(['token' => null, 'expire_at' => null]);

                return redirect('/auth_error')
                    ->with('message', Lang::get('message.ERR_TOKEN_EXPIRED'));
            }

            // ログイン処理
            $user->update(['token' => null, 'expire_at' => null]);
            $login_request = (new LoginRequest)->merge([
                'email' => $request->email,
                'password' => 'verified',
            ]);

            return $this->store($login_request);
        } else {
            // 不正アクセス対応
            return redirect('/auth_error')
                ->with('message', Lang::get('message.ERR_LOGIN'));
        }
    }

}
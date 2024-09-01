<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ユーザー登録後のリダイレクト先指定
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/thanks_register');
            }
        });

        // ログアウト後のリダイレクト先指定
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/login');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
		Fortify::createUsersUsing(CreateNewUser::class);

		Fortify::registerView(function () {
            return view('auth.register');
		});

		Fortify::loginView(function () {
            return view('auth.login');
		});

        Fortify::verifyEmailView(function () {
            return view('auth.verify_email');
        });

		RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            // Fortify標準ログイン処理の中のパスワードチェックを除外
            // if ($user && Hash::check($request->password, $user->password)) {
            //     return $user;
            // }

            // ※補足
            // この処理が実行されるのは2段階認証の内の「二段階目」であり
            // 既に「一段階目」でパスワードチェックは完了しているので不要

            return $user;
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
    }
}

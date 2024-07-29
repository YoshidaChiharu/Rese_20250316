<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // 登録完了ページ表示
    public function showThanksRegister () {
        return view('auth/thanks_register');
    }
}
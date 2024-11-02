<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class MyShopsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 自身が作成した店舗以外の情報を表示しようとした場合はトップページへリダイレクト
        // （※URL直打ちで他店舗の情報が見れてしまうのを防止する意図）
        $id = Auth::id();
        $owner_id = Shop::find($request->shop_id)->owner_id;
        if ($id !== $owner_id) {
            return redirect('/');
        }

        return $next($request);
    }
}

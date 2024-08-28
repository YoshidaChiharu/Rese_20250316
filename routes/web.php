<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;

    // Route::post('/register', [RegisterController::class, 'store']);

    Route::middleware(['verified', 'auth'])->group(function () {
        Route::get('/thanks_register', [AuthController::class, 'showThanksRegister']);
        Route::get('/', [ShopController::class, 'index']);
        Route::post('/', [ShopController::class, 'updateFavorites']);
        Route::get('/detail/{shop_id}', [ShopController::class, 'showShopDetail']);
        Route::post('/detail/{shop_id}', [ShopController::class, 'reserve']);
        Route::get('/done', [ShopController::class, 'showThanksReserve']);
        Route::get('/mypage', [ShopController::class, 'showMypage']);
        Route::post('/mypage', [ShopController::class, 'deleteMyData']);
        Route::post('/mypage/update_reserve', [ShopController::class, 'updateReserve']);
        Route::post('/mypage/review', [ShopController::class, 'storeReview']);
    });
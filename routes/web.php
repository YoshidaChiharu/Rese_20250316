<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;

    Route::get('/', [ShopController::class, 'index']);
    Route::get('/detail', [ShopController::class, 'showShopDetail']);
    Route::get('/done', [ShopController::class, 'showThanksReserve']);
    Route::get('/mypage', [ShopController::class, 'showMypage']);

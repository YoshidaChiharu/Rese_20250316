<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;

    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/thanks_register', [AuthController::class, 'showThanksRegister']);
    Route::get('/', [ShopController::class, 'index']);
    Route::post('/', [ShopController::class, 'updateFavorites']);
    Route::get('/detail', [ShopController::class, 'showShopDetail']);
    Route::get('/done', [ShopController::class, 'showThanksReserve']);
    Route::get('/mypage', [ShopController::class, 'showMypage']);

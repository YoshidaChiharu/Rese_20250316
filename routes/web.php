<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Route::post('/register', [RegisterController::class, 'store']);
Route::get('/auth_first', [AuthController::class, 'showMailAnnounce']);
Route::post('/auth_first', [AuthController::class, 'authFirst']);
Route::get('/auth_second', [AuthController::class, 'authSecond']);
Route::get('/auth_error', [AuthController::class, 'showAuthError']);

Route::group(['middleware' => ['verified', 'auth']], function () {
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

Route::group(['middleware' => ['verified', 'auth', 'admin']], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/register_shop_owner', [AdminController::class, 'createShopOwner']);
        Route::post('/register_shop_owner', [AdminController::class, 'storeShopOwner']);
        Route::get('/register_shop_data', [AdminController::class, 'createShopData']);
        Route::post('/register_shop_data', [AdminController::class, 'storeShopData']);
        Route::get('/edit_shop_data', [AdminController::class, 'editShopData']);
        Route::post('/edit_shop_data', [AdminController::class, 'updateShopData']);
        Route::get('/reservation_list', [AdminController::class, 'showReservationList']);
    });
});
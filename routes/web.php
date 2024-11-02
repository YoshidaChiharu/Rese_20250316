<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;

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
    Route::get('/purchase/{reservation_id}', [PaymentController::class, 'create']);
    Route::post('/purchase/{reservation_id}', [PaymentController::class, 'store']);
    Route::get('/purchase_completed', [PaymentController::class, 'completed'])->name('payment.completed');
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['verified', 'auth', 'administrator']], function () {
        Route::get('/register_shop_owner', [AdminController::class, 'createShopOwner']);
        Route::post('/register_shop_owner', [AdminController::class, 'storeShopOwner']);
        Route::get('/admin_mail', [AdminController::class, 'createAdminMail']);
        Route::post('/admin_mail', [AdminController::class, 'sendAdminMail']);
    });

    Route::group(['middleware' => ['verified', 'auth', 'shop_owner']], function () {
        Route::get('/register_shop_data', [AdminController::class, 'createShopData']);
        Route::post('/register_shop_data', [AdminController::class, 'storeShopData']);
    });

    Route::group(['middleware' => ['verified', 'auth', 'shop_owner', 'my_shops']], function () {
        Route::get('/edit_shop_data/{shop_id}', [AdminController::class, 'editShopData']);
        Route::post('/edit_shop_data/{shop_id}', [AdminController::class, 'updateShopData']);
        Route::get('/reservation_list/{shop_id}', [AdminController::class, 'showReservationList']);
        Route::get('/reservation_list/{shop_id}/detail/{reservation_id}', [AdminController::class, 'showReservationDetail']);
        Route::get('/reservation_list/{shop_id}/detail/{reservation_id}/edit', [AdminController::class, 'editReservation']);
        Route::post('/reservation_list/{shop_id}/detail/{reservation_id}/edit', [AdminController::class, 'updateReservation']);
        Route::post('/reservation_list/{shop_id}/detail/{reservation_id}/visit', [AdminController::class, 'visitReservation']);
        Route::post('/reservation_list/{shop_id}/detail/{reservation_id}/cancel', [AdminController::class, 'cancelReservation']);
    });

});
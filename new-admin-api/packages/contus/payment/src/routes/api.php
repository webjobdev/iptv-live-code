<?php

use Contus\Payment\Api\Controllers\Payment\TransactionController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('api/admin')->namespace('Contus\Payment\Api\Controllers\Payment')->group(function () {
    Route::group ( [ 'middleware' => [ 'cors' ] ], function ()  {
        Route::group(['middleware' => 'jwt-auth'], function () {
            /** Transaction Route */
            Route::get('transactions/info', 'TransactionController@getInfo');
            Route::post('transactions/records', [TransactionController::class, 'postRecords']);
            Route::get('transactions/complete-transaction-details/{id}', 'TransactionController@getCompleteTransactionDetails');
            /**Payment Route */
            Route::get('payments/info', 'PaymentController@getInfo');
            Route::post('payments/records', 'PaymentController@postRecords');
            Route::post('payments/edit/{id}', 'PaymentController@postEdit');
            Route::post('payments/update-status/{id}', 'PaymentController@postUpdateStatus');
            Route::post('payments/update-mode/{id}', 'PaymentController@postUpdateMode');

            /** Coupon Routes **/
            Route::get('coupon/info', 'CouponController@getInfo');
            Route::post('coupon/records', 'CouponController@postRecords');
            Route::post('coupon/add', 'CouponController@addCoupon');
            Route::post('coupon/action', 'CouponController@postAction');
            Route::put('coupon/update/{id}', 'CouponController@addCoupon');
            Route::post('coupon/update-status/', 'CouponController@postUpdateStatus');
        });
        Route::post('coupon/verify', 'CouponController@verifyCoupon');
    });
});



<?php

use Contus\Customer\Api\Controllers\Customer\SubscriptionPlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('api/admin')->namespace('Contus\Customer\Api\Controllers\Customer')->group( function () {
    Route::group ( [ 'middleware' => [ 'cors' ] ], function ()  {
        Route::group(['middleware' => 'jwt-auth'], function () {
            Route::resource('customers', 'CustomerResourceController' );
            Route::post('customer-subscription', 'CustomerResourceController@addSubcription' );
            Route::get('customer/info', 'CustomerAuthController@getInfo');
            Route::post('customer/records', 'CustomerAuthController@postRecords');
            Route::post('customer/action', 'CustomerAuthController@postAction');
            Route::post('customer/update-status', 'CustomerAuthController@postUpdateStatus');
        
            /**Subscription Routes **/
            Route::get('subscriptions-plans/info', [SubscriptionPlanController::class, 'getInfo']);
            Route::post('subscriptions-plans/records', [SubscriptionPlanController::class, 'postRecords']);
            Route::post('subscriptions-plans/add', 'SubscriptionPlanController@postAdd');
            Route::post('subscriptions-plans/update-status', 'SubscriptionPlanController@postUpdateStatus');
            Route::post('subscriptions-plans/edit/{id}', [SubscriptionPlanController::class, 'postEdit']);
            Route::post('subscriptions-plans/action', [SubscriptionPlanController::class, 'postAction']);
            // Route::post('subscriptions-plans/action', 'SubscriptionPlanController@postAction');
            Route::post('subscriptions-plans/addLanguage/{id}', 'SubscriptionPlanController@addLanguage');
        });
    });
});


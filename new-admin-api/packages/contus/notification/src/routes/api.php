<?php

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

Route::group([ 'prefix' => 'api/admin','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function () {
    Route::group([ 'middleware' => [ 'cors' ] ], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {
            Route::post('notify', 'NotificationController@setNotification');
        });
    });
});


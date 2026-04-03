<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Contus\Vod\Http\Controllers\Admin\VideoOnDemandController;

Route::prefix('admin')->namespace('Contus\Vod\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function () {
        Route::get('vod', [VideoOnDemandController::class, 'getindex']);
        Route::get('video-on-demand/gridlist', [VideoOnDemandController::class, 'gridList']);
        Route::get('vod/add', [VideoOnDemandController::class, 'videoAdd']);
        Route::get('vod/vod-details-edit/{id}', [VideoOnDemandController::class, 'VodEdit']);
    });
});

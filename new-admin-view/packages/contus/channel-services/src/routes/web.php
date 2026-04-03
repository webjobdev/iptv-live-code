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

use Contus\ChannelServices\Http\Controllers\Admin\ChannelServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('Contus\ChannelServices\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function () {
        Route::get('channel-services/catch-up-tv', [ChannelServiceController::class, 'catchUpIndex']);
        Route::get('channel-services/gridlist', [ChannelServiceController::class, 'gridListIndex']);

        Route::get('channel-services/epg-service', [ChannelServiceController::class, 'epgServiceIndex']);
        Route::get('channel-services/epg-service/gridlist', [ChannelServiceController::class, 'epgServiceGridList']);

        Route::get('channel-services/live-rewind', [ChannelServiceController::class, 'liveRewindIndex']);
        Route::get('channel-services/live-rewind/gridlist', [ChannelServiceController::class, 'liveRewindGridList']);
    });
});

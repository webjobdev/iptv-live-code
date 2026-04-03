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

use Contus\Channel\Http\Controllers\Admin\ChannelController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('Contus\Channel\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function () {
        Route::get('channel', [ChannelController::class, 'getindex']);
        Route::get('channel/gridlist', [ChannelController::class, 'gridList']);
        Route::get('channel/add', [ChannelController::class, 'ChannelAdd']);
        Route::get('channel/channel-details-edit/{id}', [ChannelController::class, 'ChannelEdit']);
    });
});

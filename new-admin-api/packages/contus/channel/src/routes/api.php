<?php

use Contus\Channel\Api\Controllers\Admin\ChannelIndexController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\Channel\Api\Controller\Admin')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('channel/info', [ChannelIndexController::class, 'getInfo']);
            Route::post('create/channel', [ChannelIndexController::class, 'CreateChannel']);
            Route::post('channel/records', [ChannelIndexController::class, 'postRecords']);
            Route::post('channel/action', [ChannelIndexController::class, 'postAction']);
            Route::post('channel/edit/{id}', [ChannelIndexController::class, 'postEdit']);
            Route::get('channel/channel-to-edit/{id}', [ChannelIndexController::class, 'getChannelToEdit']);
            Route::post('channel/toggle/edit/{id}', [ChannelIndexController::class, 'postToggle']);
            Route::post('channel/delete-action', [ChannelIndexController::class, 'postDeleteAction']);
            Route::post('channel/update-status',[ChannelIndexController::class, 'postUpdateStatus']);
            Route::post('channel/bulk-fetch', [ChannelIndexController::class, 'postBulkFetch']);

            Route::post('channel/poster', [ChannelIndexController::class, 'postPosters']);
        });
    });
});

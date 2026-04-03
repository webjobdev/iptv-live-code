<?php

use Contus\BulkUpload\Api\Controllers\Admin\m3uChannelController;
use Contus\BulkUpload\Api\Controllers\Admin\m3uTvShowController;
use Contus\BulkUpload\Api\Controllers\Admin\m3uVodController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\BulkUpload\Api\Controller\Admin')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            // Route::get('m3u-channel/info', [m3uChannelController::class, 'getInfo']);
            Route::post('m3u-channel/create', [m3uChannelController::class, 'postAdd']);
            // Route::post('m3u-channel/edit/{id}', [m3uChannelController::class, 'postEdit']);
            Route::post('m3u-channel/records', [m3uChannelController::class, 'postRecords']);
            Route::post('m3u-channel/action', [m3uChannelController::class, 'postAction']);

            // Route::get('m3u-vod/info', [m3uVodController::class, 'getInfo']);
            Route::post('m3u-vod/create', [m3uVodController::class, 'postAdd']);
            // Route::post('m3u-vod/edit/{id}', [m3uVodController::class, 'postEdit']);
            Route::post('m3u-vod/records', [m3uVodController::class, 'postRecords']);
            Route::post('m3u-vod/action', [m3uVodController::class, 'postAction']);

            // Route::get('m3u-tv-show/info', [m3uTvShowController::class, 'getInfo']);
            Route::post('m3u-tv-show/create', [m3uTvShowController::class, 'postAdd']);
            // Route::post('m3u-tv-show/edit/{id}', [m3uTvShowController::class, 'postEdit']);
            Route::post('m3u-tv-show/records', [m3uTvShowController::class, 'postRecords']);
            Route::post('m3u-tv-show/action', [m3uTvShowController::class, 'postAction']);

        });
    });
});
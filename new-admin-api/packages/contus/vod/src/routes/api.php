<?php

use Contus\Vod\Api\Controllers\Admin\VodIndexController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\Vod\Api\Controller\Admin')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {
            Route::get('vod/info', [VodIndexController::class, 'getInfo']);
            Route::post('create/video-on-demand', [VodIndexController::class, 'CreateVod']);
            Route::post('video-on-demand/records', [VodIndexController::class, 'postRecords']);
            Route::post('video-on-demand/edit/{id}', [VodIndexController::class, 'postEdit']);
            Route::get('video-on-demand/vod-to-edit/{id}', [VodIndexController::class, 'getVodToEdit']);
            Route::post('video-on-demand/toggle/edit/{id}', [VodIndexController::class, 'postToggle']);
            Route::post('video-on-demand/action', [VodIndexController::class, 'postAction']);
            Route::post('video-on-demand/update-status', [VodIndexController::class, 'postUpdateStatus']);

            Route::post('vod/thumbnail', [VodIndexController::class, 'postThumbnail']);
            Route::post('vod/poster', [VodIndexController::class, 'postPosters']);
        });
    });
});

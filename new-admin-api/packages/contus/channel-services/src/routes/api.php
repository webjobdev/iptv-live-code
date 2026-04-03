<?php

use Contus\ChannelServices\Api\Controllers\Admin\CatchUpIndexController;
use Contus\ChannelServices\Api\Controllers\Admin\EpgServiceController;
use Contus\ChannelServices\Api\Controllers\Admin\LiveRewindController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\ChannelServices\Api\Controller\Admin')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('catch-up/info', [CatchUpIndexController::class, 'getInfo']);
            Route::post('create/catch-up', [CatchUpIndexController::class, 'CreateCatchUp']);
            Route::post('edit/catch-up/{id}', [CatchUpIndexController::class, 'postEdit']);
            Route::post('catch-up/toggle/edit/{id}', [CatchUpIndexController::class, 'postToggleEdit']);
            Route::post('catch-up/records', [CatchUpIndexController::class, 'postRecords']);
            Route::post('catch-up/action', [CatchUpIndexController::class, 'postAction']);

            Route::get('epg-service/info', [EpgServiceController::class, 'getInfo']);
            Route::post('create/epg-service', [EpgServiceController::class, 'Create']);
            Route::post('edit/epg-service/{id}', [EpgServiceController::class, 'postEdit']);
            Route::post('epg-service/toggle/edit/{id}', [EpgServiceController::class, 'postToggleEdit']);
            Route::post('epg-service/records', [EpgServiceController::class, 'postRecords']);
            Route::post('epg-service/run/{id}', [EpgServiceController::class, 'postRun']);
            Route::post('epg-service/action', [EpgServiceController::class, 'postAction']);

            Route::get('live-rewind/info', [LiveRewindController::class, 'getInfo']);
            Route::post('create/live-rewind', [LiveRewindController::class, 'CreateRewind']);
            Route::post('edit/live-rewind/{id}', [LiveRewindController::class, 'postEdit']);
            Route::post('live-rewind/toggle/edit/{id}', [LiveRewindController::class, 'postToggleEdit']);
            Route::post('live-rewind/records', [LiveRewindController::class, 'postRecords']);
            Route::post('live-rewind/action', [LiveRewindController::class, 'postAction']);

        });
    });
});

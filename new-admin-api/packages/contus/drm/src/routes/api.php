<?php

use Contus\Drm\Api\Controllers\DrmController;
use Contus\Drm\Api\Controllers\DrmDetailAddController;
use Contus\Drm\Api\Controllers\DrmProfileDetailsController;
use Contus\Drm\Model\DrmProfileDetails;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\Drm\Api\Controllers')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('drm/info', [DrmController::class, 'getInfo']);
            Route::post('drm/add', [DrmController::class, 'postAdd']);
            Route::post('drm/records', [DrmController::class, 'postRecords']);
            Route::post('drm/action', [DrmController::class, 'postAction']);
            Route::post('drm/update-status', [DrmController::class, 'postUpdateStatus']);

            Route::post('drm/detail/add', [DrmDetailAddController::class, 'postAdd']);

            Route::post('drm/profile/detail/add', [DrmProfileDetailsController::class, 'postAdd']);
            Route::post('drm/profile/records', [DrmProfileDetailsController::class, 'postRecords']);
            Route::post('drm/profile/toggle-status', [DrmProfileDetailsController::class, 'togglehStatus']);

        });
    });
});



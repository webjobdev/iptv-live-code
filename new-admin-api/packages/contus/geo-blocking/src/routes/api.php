<?php

use Contus\GeoBlocking\Api\Controllers\GeoRestrictionController;
use Contus\GeoBlocking\Api\Controllers\IpRestrictionController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\GeoBlocking\Api\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        // geo-restrictions
        Route::get('geo-blocking/geo-restrictions/info', [GeoRestrictionController::class, 'getInfo']); // index page
        Route::post('geo-blocking/geo-restrictions/records', [GeoRestrictionController::class, 'postRecords']); // gridlist view
        Route::post('geo-blocking/geo-restrictions/action', [GeoRestrictionController::class, 'postAction']); // action
        Route::post('geo-blocking/geo-restrictions/create', [GeoRestrictionController::class, 'postCreate']); // action
        Route::post('geo-blocking/geo-restrictions/edit/{id}', [GeoRestrictionController::class, 'postEdit']); // action

        // ip-restrictions
        Route::get('geo-blocking/ip-restrictions/info', [IpRestrictionController::class, 'getInfo']); // index page
        Route::post('geo-blocking/ip-restrictions/records', [IpRestrictionController::class, 'postRecords']); // gridlist view
        Route::post('geo-blocking/ip-restrictions/action', [IpRestrictionController::class, 'postAction']); // action
        Route::post('geo-blocking/ip-restrictions/create', [IpRestrictionController::class, 'postCreate']); // action
        Route::post('geo-blocking/ip-restrictions/edit/{id}', [IpRestrictionController::class, 'postEdit']); // action
    });
});

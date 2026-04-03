<?php

use Illuminate\Support\Facades\Route;
use Contus\Devices\Api\Controllers\DeviceController;

Route::prefix('api/admin')->namespace('Contus\Devices\Api\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('divice/info', [DeviceController::class, 'getInfo']); // index page
        Route::post('divice/records', [DeviceController::class, 'postRecords']); // gridlist view
        Route::post('divice/action', [DeviceController::class, 'postAction']); // action
        Route::post('divice/search-record', [DeviceController::class, 'postSearch']); // action

        Route::post('divice/add', [DeviceController::class, 'postAdd']); // add new permission rule
        Route::post('divice/edit/{id}', [DeviceController::class, 'postEdit']); // edit existing permission rule
        Route::post('divice/destroy/{id}', [DeviceController::class, 'deleteDevice'])->name('divice.destroy'); // Delete record

        // add multiple device
        Route::post('divice/add-multiple-device', [DeviceController::class, 'postAddMultiple']); // add new permission rule
    });
});

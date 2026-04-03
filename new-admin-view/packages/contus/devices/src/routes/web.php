<?php

use Illuminate\Support\Facades\Route;
use Contus\Devices\Http\Controllers\DeviceController;

Route::prefix('admin')->namespace('Contus\Device\Http\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('divice', [DeviceController::class, 'index'])->name('divice.index'); // index page
        Route::get('divice/gridlist', [DeviceController::class, 'getGridlist']); // gridlist view

        Route::get('divice/add', [DeviceController::class, 'addDevices'])->name('divice.add-single-device'); // add new devices
        Route::get('divice/multiple-add', [DeviceController::class, 'addMultipleDevices'])->name('divice.add-multiple-device'); // add multiple devices
        Route::get('divice/edit/{id}', [DeviceController::class, 'editDevices']); // edit existing devices

        Route::get('api/timezones', [DeviceController::class, 'getTimezones']); // get timezones
    });
});

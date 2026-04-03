<?php

use Illuminate\Support\Facades\Route;
use Contus\GeoBlocking\Http\Controllers\GeoRestrictionController;

Route::prefix('admin/geo-blocking/')->namespace('Contus\GeoBlocking\Http\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        // geo restrictions
        Route::get('geo-restrictions', [GeoRestrictionController::class, 'index'])->name('geo-restriction.index');
        Route::get('geo-restrictions/add', [GeoRestrictionController::class, 'addGeoRestriction'])->name('geo-restriction.add');
        Route::get('geo-restrictions/edit/{id}', [GeoRestrictionController::class, 'addGeoRestriction'])->name('geo-restriction.edit');
        Route::get('geo-restrictions/gridlist', [GeoRestrictionController::class, 'getGeoGridlist']);

        // ip restrictions
        Route::get('ip-restrictions', [GeoRestrictionController::class, 'index1'])->name('ip-restriction.index');
        Route::get('ip-restrictions/add', [GeoRestrictionController::class, 'addIpRestriction'])->name('ip-restriction.add');
        Route::get('ip-restrictions/edit/{id}', [GeoRestrictionController::class, 'addIpRestriction'])->name('ip-restriction.edit');
        Route::get('ip-restrictions/gridlist', [GeoRestrictionController::class, 'getIpGridlist']);
    });
});

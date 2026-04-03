<?php

use Illuminate\Support\Facades\Route;
use Contus\Drm\Http\Controllers\DrmController;
use Contus\Drm\Http\Controllers\DrmDetailAddController;
use Contus\Drm\Http\Controllers\DrmAddProfileController;

Route::prefix('admin')->namespace('Contus\Drm\Http\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('drm', [DrmController::class, 'index'])->name('drm.index');
        Route::get('drm/gridlist', [DrmController::class, 'getGridlist']);
        Route::delete('/drm/destroy', [DrmController::class, 'destroy'])->name('drm.destroy');

        Route::get('drm/detail/add/{id}', [DrmDetailAddController::class, 'index'])->name('drm.detail.add');

        Route::get('drm/profile/add/{id}', [DrmAddProfileController::class, 'index'])->name('drm.profile.add');
    });
});

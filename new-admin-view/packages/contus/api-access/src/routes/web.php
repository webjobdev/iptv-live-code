<?php

use Illuminate\Support\Facades\Route;
use Contus\ApiAccess\Http\Controllers\ApiAccessController;

Route::prefix('admin')->namespace('Contus\ApiAccess\Http\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('api-access', [ApiAccessController::class, 'index'])->name('api-access.index');
        Route::get('api-access/gridlist', [ApiAccessController::class, 'getGridlist']);

        Route::get('api-access/add', [ApiAccessController::class, 'addApiUser']);
        Route::get('api-access/edit/{id}', [ApiAccessController::class, 'editApiUser']);
    });
});

<?php

use Illuminate\Support\Facades\Route;
use Contus\SystemUser\Http\Controllers\SystemUserController;

Route::prefix('admin')->namespace('Contus\ApiAccess\Http\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('system-user', [SystemUserController::class, 'index'])->name('system-user.index');
        Route::get('system-user/gridlist', [SystemUserController::class, 'getGridlist']);

        Route::get('system-user/add', [SystemUserController::class, 'addApiUser']);
        Route::get('system-user/edit/{id}', [SystemUserController::class, 'editApiUser']);

        Route::get('system-user/download-user-log/{id}', [SystemUserController::class, 'downloadUserLog']);
    });
});

<?php

use Contus\SystemUser\Api\Controllers\SystemUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\SystemUser\Api\Controllers')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('system-user/info', [SystemUserController::class, 'getInfo']);
            Route::post('system-user/records', [SystemUserController::class, 'postRecords']); // get records
            Route::post('system-user/action', [SystemUserController::class, 'postAction']);

            Route::post('system-user/add', [SystemUserController::class, 'postAdd']); // add record
            Route::post('system-user/edit/{id}', [SystemUserController::class, 'postEdit']); // update record
            Route::post('system-user/status-update', [SystemUserController::class, 'postStatusEdit']); // update status
            Route::post('system-user/delete/{id}', [SystemUserController::class, 'postRemove']); // delete record


        });
    });
});

//Download Use Log Information
// Route::get('system-user/download-user-log/{id}', [SystemUserController::class, 'downloadUserLog']); // Download User Log
Route::get('system-user/download-user-log/{id}', [SystemUserController::class, 'downloadUserLog']);    // Download User Log


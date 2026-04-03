<?php

use Contus\ApiAccess\Api\Controllers\ApiAccessController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\ApiAccess\Api\Controllers')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('api-access/info', [ApiAccessController::class, 'getInfo']);
            Route::post('api-access/records', [ApiAccessController::class, 'postRecords']); // get records
            Route::post('api-access/action', [ApiAccessController::class, 'postAction']); // get records

            Route::post('api-access/add', [ApiAccessController::class, 'postAdd']); // add record
            Route::post('api-access/edit/{id}', [ApiAccessController::class, 'postEdit']); // update record
            Route::post('api-access/status-update', [ApiAccessController::class, 'postStatusEdit']); // update status
            Route::post('api-access/remove/{id}', [ApiAccessController::class, 'postRemove']); // update status

            Route::post('organizations-fetch/mon-plan', [ApiAccessController::class, 'postMonPlan']); // update status
        });
    });
});

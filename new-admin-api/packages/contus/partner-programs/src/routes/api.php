<?php

use Contus\PartnerProgram\Api\Controllers\PartnerProgramController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin/')->namespace('Contus\PartnerProgram\Api\Controllers')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('partner-programs/info', [PartnerProgramController::class, 'getInfo']);
            Route::post('partner-programs/records', [PartnerProgramController::class, 'postRecords']); // get records

            Route::post('partner-programs/add', [PartnerProgramController::class, 'postAdd']); // add record
            Route::post('partner-programs/edit/{id}', [PartnerProgramController::class, 'postEdit']); // update record
            Route::get('partner-programs/view/{id}', [PartnerProgramController::class, 'postView']); // view record
            Route::post('partner-programs/status-update', [PartnerProgramController::class, 'postStatusEdit']); // update status
            Route::post('partner-programs/action', [PartnerProgramController::class, 'postAction']); // update status
            Route::post('partner-programs/remove/{id}', [PartnerProgramController::class, 'postRemove']); // update status

        });
    });
});

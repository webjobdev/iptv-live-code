<?php

use Contus\StreamServices\Api\Controllers\StreamingUrlPolicyController;
use Contus\StreamServices\Api\Controllers\StreamSettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin/stream-services/')->namespace('Contus\StreamServices\Api\Controllers')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            // Streaming Url Policy
            Route::get('streaming-url-policy/info', [StreamingUrlPolicyController::class, 'getInfo']);
            Route::post('streaming-url-policy/records', [StreamingUrlPolicyController::class, 'postRecords']); // get records

            Route::post('streaming-url-policy/add', [StreamingUrlPolicyController::class, 'postAdd']); // add record
            Route::post('streaming-url-policy/edit/{id}', [StreamingUrlPolicyController::class, 'postEdit']); // update record
            Route::get('streaming-url-policy/view/{id}', [StreamingUrlPolicyController::class, 'postView']); // view record
            Route::post('streaming-url-policy/status-update', [StreamingUrlPolicyController::class, 'postStatusEdit']); // update status
            Route::post('streaming-url-policy/action', [StreamingUrlPolicyController::class, 'postAction']); // update status

            Route::post('search-record', [StreamingUrlPolicyController::class, 'searchRecord']); // search record by name
            Route::post('streaming-url-policy/delete-record/{id}', [StreamingUrlPolicyController::class, 'removeRecord']); // search record by name


            // Stream Settings
            Route::get('stream-settings/info', [StreamSettingsController::class, 'getInfo']);
            Route::post('stream-settings/records', [StreamSettingsController::class, 'postRecords']); // get records

            Route::post('stream-settings/add', [StreamSettingsController::class, 'postAdd']); // add record
            Route::post('stream-settings/edit/{id}', [StreamSettingsController::class, 'postEdit']); // update record
            Route::post('status-update', [StreamSettingsController::class, 'postStatusEdit']); // update status

            Route::post('search-record', [StreamSettingsController::class, 'searchRecord']); // search record by name
        });
    });
});

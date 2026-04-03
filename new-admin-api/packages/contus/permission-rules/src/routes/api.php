<?php

use Contus\PermissionRule\Api\Controllers\PermissionRuleController;
use Contus\User\Api\Controllers\RulePermissions\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin/')->namespace('Contus\PermissionRule\Api\Controllers')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('permission-rules/info', [PermissionRuleController::class, 'getInfo']);
            Route::post('permission-rules/records', [PermissionRuleController::class, 'postRecords']); // get records

            Route::post('permission-rules/add', [PermissionRuleController::class, 'postAdd']); // add record
            Route::post('permission-rules/edit/{id}', [PermissionRuleController::class, 'postEdit']); // update record
            Route::post('permission-rules/action', [PermissionRuleController::class, 'postAction']); // perform action on records

            Route::post('permission-rules/destroy/{id}', [PermissionRuleController::class, 'deleteRule'])->name('permission.destroy'); // Delete record
            Route::post('permission-rules/organizations/records', [PermissionRuleController::class, 'getAllowedOrgs'])->name('organization.list'); // get allowed organizations
        });
    });
});

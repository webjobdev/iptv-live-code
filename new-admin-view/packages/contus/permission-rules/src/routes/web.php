<?php

use Illuminate\Support\Facades\Route;
use Contus\PermissionRule\Http\Controllers\PermissionRuleController;

Route::prefix('admin')->namespace('Contus\PermissionRule\Http\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('permission-rules', [PermissionRuleController::class, 'index'])->name('permission.index'); // index page
        Route::get('permission-rules/gridlist', [PermissionRuleController::class, 'getGridlist']); // gridlist view

        Route::get('permission-rules/add', [PermissionRuleController::class, 'addPermissionRule']); // add new permission rule
        Route::get('permission-rules/edit/{id}', [PermissionRuleController::class, 'editPermissionRule']); // edit existing permission rule
        Route::delete('permission-rules/destroy', [PermissionRuleController::class, 'deleteRule'])->name('permission.destroy'); // Delete record
    });
});

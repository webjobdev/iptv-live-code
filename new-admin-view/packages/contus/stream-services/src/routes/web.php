<?php

use Contus\StreamServices\Http\Controllers\StreamingUrlPolicyController;
use Contus\StreamServices\Http\Controllers\StreamSettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/stream-services/')->namespace('Contus\StreamServices\Http\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        // Streaming Url Policy
        Route::get('streaming-url-policy', [StreamingUrlPolicyController::class, 'index'])->name('stream-url-policy.index');
        Route::get('streaming-url-policy/gridlist', [StreamingUrlPolicyController::class, 'getGridlist']);
        Route::get('streaming-url-policy/add', [StreamingUrlPolicyController::class, 'addPolicy'])->name('stream-url-policy.create');
        Route::get('streaming-url-policy/edit/{id}', [StreamingUrlPolicyController::class, 'editPolicy'])->name('stream-url-policy.edit');
        Route::get('streaming-url-policy/view/{id}', [StreamingUrlPolicyController::class, 'viewPolicy'])->name('stream-url-policy.view');

        // Stream Settings
        Route::get('stream-settings', [StreamSettingsController::class, 'index'])->name('stream-settings.index');
        Route::get('stream-settings/gridlist', [StreamSettingsController::class, 'getGridlist']);
        Route::get('stream-settings/add', [StreamSettingsController::class, 'addSettings'])->name('stream-settings.create');
        Route::get('stream-settings/edit/{id}', [StreamSettingsController::class, 'editSettings'])->name('stream-settings.edit');
    });
});

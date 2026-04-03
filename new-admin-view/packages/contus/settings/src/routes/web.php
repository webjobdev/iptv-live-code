<?php

use Contus\Settings\Http\Controllers\Admin\ExtensionController;
use Contus\Settings\Http\Controllers\Admin\PaymentServicesController;
use Illuminate\Support\Facades\Route;
use Contus\settings\Http\Controllers\Admin\SettingIndexControllers;

Route::prefix('admin')->namespace('Contus\Settings\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('general/settings', [SettingIndexControllers::class, 'index']);
        Route::get('general/settings/gridlist', [SettingIndexControllers::class, 'getgridList']);

        Route::get('general/email-settings', [SettingIndexControllers::class, 'emailIndex']);
        Route::get('general/email-settings/gridlist', [SettingIndexControllers::class, 'getEmailGridList']);

        // ========================================************ Payment Services Start ************===========================================

        // payment services
        Route::get('setting/payment-services', [PaymentServicesController::class, 'serviceIndex']);
        Route::get('setting/payment-services/add', [PaymentServicesController::class, 'CreateService']);
        Route::get('setting/payment-services/edit/{id}', [PaymentServicesController::class, 'EditService'])->name('setting.payment-services.edit');
        Route::get('payment-services/gridlist', [PaymentServicesController::class, 'serviceGridlist']);

        Route::get('setting/payment-services/currency', [PaymentServicesController::class, 'CurrencyIndex']);
        Route::get('payment-services/currency/gridlist', [PaymentServicesController::class, 'CurrencyGridView']);

        Route::get('setting/payment-services/currency-converter', [PaymentServicesController::class, 'ConverterIndex']);
        Route::get('payment-services/currency-converter/gridlist', [PaymentServicesController::class, 'ConverterGridView']);
        // ========================================************ Payment Services End ************=============================================

        // ========================================************ Extensions Services Start ************===========================================
        // play back token
        Route::get('setting/play-back-token', [ExtensionController::class, 'index']);
        Route::get('play-back-token/setting/gridlist', [ExtensionController::class, 'gridView']);

        // redirect
        Route::get('setting/device-redirect', [ExtensionController::class, 'RedirectIndex']);
        Route::get('device-redirect/setting/gridlist', [ExtensionController::class, 'RedirectgridView']);
        // ========================================************ Extensions Services End ************=============================================

        // ========================================************ Dashboards Configuration start ************===========================================
        Route::get('dashboard-configuration', [ExtensionController::class, 'dashIndex']);
        // ========================================************ Dashboards Configuration End ************=============================================

        // ========================================************ M3U Channel start ************===========================================
        Route::get('m3u-channel', [ExtensionController::class, 'm3uIndex']);
        Route::get('m3u-channel/gridlist', [ExtensionController::class, 'm3uGridView']);

        Route::get('m3u-vod', [ExtensionController::class, 'm3uVodIndex']);
        Route::get('m3u-vod/gridlist', [ExtensionController::class, 'm3uVodGridView']);

        Route::get('m3u-tvshow', [ExtensionController::class, 'm3uTvShowIndex']);
        Route::get('m3u-tvshow/gridlist', [ExtensionController::class, 'm3uTvShowGridView']);
        // ========================================************ M3U Channel End ************=============================================
    });
});

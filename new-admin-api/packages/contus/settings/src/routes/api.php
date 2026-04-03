<?php

use Contus\Settings\Api\Controllers\Admin\EmailSettingController;
use Contus\Settings\Api\Controllers\Admin\Extensions\DeviceRedirectController;
use Contus\Settings\Api\Controllers\Admin\m3u_channel\m3uChannelController;
use Contus\Settings\Api\Controllers\Admin\PaymentService\PaymentServiceController;
use Contus\Settings\Api\Controllers\Admin\PaymentService\PaymentServiceCurrencyController;
use Contus\Settings\Api\Controllers\Admin\DashboardConfigurationController;
use Contus\Settings\Api\Controllers\Admin\PaymentService\PaymentServiceCurrencyConverterController;
use Contus\Settings\Api\Controllers\Admin\Extensions\PlayBackTokenController;
use Contus\Settings\Api\Controllers\Admin\SettingIndexController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\Settings\Api\Controller\Admin')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('setting/info', [SettingIndexController::class, 'getInfo']);
            Route::post('subacriber/setting/add', [SettingIndexController::class, 'postAdd']); 
            Route::post('subacriber/setting/edit/{id}', [SettingIndexController::class, 'postEdit']);
            Route::post('general/settings/action', [SettingIndexController::class, 'postAction']);
            Route::post('general/settings/update-status', [SettingIndexController::class, 'postUpdateStatus']);
            Route::post('general/settings/records', [SettingIndexController::class, 'postRecords']);

            Route::post('general/settings/get/records', [SettingIndexController::class, 'getRecords']);

            // general setting
            Route::get('general/settings/info', [EmailSettingController::class, 'getInfo']);
            // Route::post('general/settings/records', [EmailSettingController::class, 'postRecords']);
            Route::post('general-settings/records', [EmailSettingController::class, 'postRecords']);
            Route::post('general-settings/get-records', [EmailSettingController::class, 'getSettingsRecords']);
            Route::post('general-settings/save-setting', [EmailSettingController::class, 'saveRecords']);
            Route::post('general-settings/save-tenant-settings', [EmailSettingController::class, 'saveTenantRecords']);
            // email setting
            Route::get('general/email-setting/info', [EmailSettingController::class, 'getInfo']);
            Route::post('general/email-setting/add', [EmailSettingController::class, 'postAdd']);
            Route::post('general/email-setting/edit/{id}', [EmailSettingController::class, 'postEdit']);
            Route::post('general/email-settings/action', [EmailSettingController::class, 'postAction']);
            Route::post('general/email-settings/update-status', [EmailSettingController::class, 'postUpdateStatus']);
            Route::post('general/email-settings/records', [EmailSettingController::class, 'postRecords']);

            // payment services
            Route::get('payment-service/info', [PaymentServiceController::class, 'getInfo']);
            Route::post('payment-service/create', [PaymentServiceController::class, 'postCreate']);
            Route::post('payment-service/edit/{id}', [PaymentServiceController::class, 'postEdit']);
            Route::post('payment-service/toggle/edit/{id}', [PaymentServiceController::class, 'postToggle']);
            Route::post('payment-service/default/edit/{id}', [PaymentServiceController::class, 'postDefault']);
            Route::post('payment-service/records', [PaymentServiceController::class, 'postRecords']);
            Route::post('payment-service/action', [PaymentServiceController::class, 'postAction']);

            Route::get('payment-service/currency/info', [PaymentServiceCurrencyController::class, 'getInfo']);
            Route::post('payment-service/currency/create', [PaymentServiceCurrencyController::class, 'postAdd']);
            Route::post('payment-service/currency/edit/{id}', [PaymentServiceCurrencyController::class, 'postEdit']);
            Route::post('payment-service/currency/records', [PaymentServiceCurrencyController::class, 'postRecords']);
            Route::post('payment-service/currency/action', [PaymentServiceCurrencyController::class, 'postAction']);

            Route::get('payment-service/currency-converter/info', [PaymentServiceCurrencyConverterController::class, 'getInfo']);
            Route::post('payment-service/currency-converter/create', [PaymentServiceCurrencyConverterController::class, 'postAdd']);
            Route::post('payment-service/currency-converter/edit/{id}', [PaymentServiceCurrencyConverterController::class, 'postEdit']);
            Route::post('payment-service/currency-converter/records', [PaymentServiceCurrencyConverterController::class, 'postRecords']);
            Route::post('payment-service/currency-converter/action', [PaymentServiceCurrencyConverterController::class, 'postAction']);

            // ========================================************ Extensions Services Start ************===========================================
            // play back token
            Route::get('setting/play-back-token/info', [PlayBackTokenController::class, 'getInfo']);
            Route::post('setting/play-back-token/create', [PlayBackTokenController::class, 'postAdd']);
            Route::post('setting/play-back-token/edit/{id}', [PlayBackTokenController::class, 'postEdit']);
            Route::post('setting/play-back-token/toggle/edit/{id}', [PlayBackTokenController::class, 'postToggle']);
            Route::post('setting/play-back-token/records', [PlayBackTokenController::class, 'postRecords']);
            Route::post('setting/play-back-token/action', [PlayBackTokenController::class, 'postAction']);

            // device redirect
            Route::get('setting/device-redirect/info', [DeviceRedirectController::class, 'getInfo']);
            Route::post('setting/device-redirect/create', [DeviceRedirectController::class, 'postAdd']);
            Route::post('setting/device-redirect/edit/{id}', [DeviceRedirectController::class, 'postEdit']);
            Route::post('setting/device-redirect/records', [DeviceRedirectController::class, 'postRecords']);
            Route::post('setting/device-redirect/action', [DeviceRedirectController::class, 'postAction']);
            // ========================================************ Extensions Services End ************=============================================

            // ========================================************ Dashboards Configuration start ************===========================================
            Route::get('dashboard-configuration/info', [DashboardConfigurationController::class, 'getInfo']);
            Route::post('dashboard-configuration/records', [DashboardConfigurationController::class, 'postRecords']);
            Route::post('dashboard-configuration/edit', [DashboardConfigurationController::class, 'postUpdate']);
            // ========================================************ Dashboards Configuration End ************=============================================

            // ========================================************ M3U Channel start ************=======================================================
            // Route::get('m3u-channel/info', [m3uChannelController::class, 'getInfo']);
            // Route::post('m3u-channel/create', [m3uChannelController::class, 'postAdd']);
            // Route::post('m3u-channel/edit/{id}', [m3uChannelController::class, 'postEdit']);
            // Route::post('m3u-channel/records', [m3uChannelController::class, 'postRecords']);
            // Route::post('m3u-channel/action', [m3uChannelController::class, 'postAction']);
            // ========================================************ M3U Channel End ************=========================================================
        });
    });
});

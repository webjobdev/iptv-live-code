<?php

use Contus\Reports\Api\Controllers\Admin\ActivationGenerateReportController;
use Contus\Reports\Api\Controllers\Admin\ActivationReportController;
use Contus\Reports\Api\Controllers\Admin\CpsReportController;
use Contus\Reports\Api\Controllers\Admin\SubscriberReportController;
use Contus\Reports\Api\Controllers\Admin\SubscriberGenerateReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\Reports\Api\Controllers\Admin')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {
            // ==========***********==========
            Route::get('subscriber-reports/info', [SubscriberReportController::class, 'getInfo']);
            Route::post('subscriber-reports/create', [SubscriberReportController::class, 'postCreate']);
            Route::post('subscriber-reports/generate', [SubscriberReportController::class, 'postGenerate']);
            Route::post('subscriber-reports/records', [SubscriberReportController::class, 'postRecords']);
            Route::post('subscriber-reports/action', [SubscriberReportController::class, 'postAction']);
            Route::post('subscriber-reports/generate-report/{id}', [SubscriberReportController::class, 'report']);
            Route::post('subscriber-reports/generate-report/pdf/{id}', [SubscriberReportController::class, 'downloadPdf']);

            Route::post('subscriber-generate-reports/records', [SubscriberGenerateReportController::class, 'postRecords']);
            Route::post('subscriber-generate-reports/action', [SubscriberGenerateReportController::class, 'postAction']);
            // ==========***********==========

            // ==========***********==========
            Route::get('cps-reports/info', [CpsReportController::class, 'getInfo']);
            Route::post('cps-reports/create', [CpsReportController::class, 'postCreate']);
            Route::post('cps-reports/records', [CpsReportController::class, 'postRecords']);
            Route::post('cps-reports/action', [CpsReportController::class, 'postAction']);

            Route::post('cps-reports/chart-data/records', [CpsReportController::class, 'RecordFetch']);
            Route::post('cps-reports/chart-data/action', [CpsReportController::class, 'postAction']);
            // ==========***********==========

            // ==========***********==========
            Route::get('activation-reports/info', [ActivationReportController::class, 'getInfo']);
            Route::post('activation-reports/create', [ActivationReportController::class, 'postCreate']);
            Route::post('activation-reports/generate', [ActivationReportController::class, 'postGenerate']);
            Route::post('activation-reports/generate-report/{id}', [ActivationReportController::class, 'report']);
            Route::post('activation-reports/records', [ActivationReportController::class, 'postRecords']);
            Route::post('activation-reports/action', [ActivationReportController::class, 'postAction']);

            Route::post('activation-generate-reports/records', [ActivationGenerateReportController::class, 'postRecords']);
            Route::post('activation-generate-reports/action', [ActivationReportController::class, 'postAction']);
            // ==========***********==========
        });
    });
});

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Contus\Reports\Http\Controllers\Admin\ActivationReportController;
use Contus\Reports\Http\Controllers\Admin\CpsReportcontroller;
use Contus\Reports\Http\Controllers\Admin\ReportsIndexController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('Contus\Reports\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function ()  {

        Route::get('subscriber-reports', [ReportsIndexController::class, 'subscriberIndex'])->name('subscriber.report');
        Route::get('subscriber/save-templates', [ReportsIndexController::class, 'saveTemplateIndex'])->name('subscriber.save.template');
        Route::get('subscriber/save-templates/gridlist', [ReportsIndexController::class, 'templatesGridList']);
        Route::get('subscriber/generate', [ReportsIndexController::class, 'generateIndex'])->name('subscriber.activation');
        Route::get('generate/gridlist', [ReportsIndexController::class, 'generateGridList']);

        Route::get('subscriber-reports/generate-report/pdf/{id}', [ReportsIndexController::class, 'downloadPdf']);
        Route::get('subscriber-reports/generate-report/csv/{id}', [ReportsIndexController::class, 'exportCsv']);
        Route::get('subscriber-reports/generate-report/table/{id}', [ReportsIndexController::class, 'exportTable']);

        Route::get('subscriber-reports/api/records', [ReportsIndexController::class, 'record']);

        Route::get('cps-reports', [ReportsIndexController::class, 'cpsIndex'])->name('cps.report');
        Route::get('cps/save-templates', [ReportsIndexController::class, 'saveIndex'])->name('cps.save.template');
        Route::get('cps/save-templates/gridlist', [ReportsIndexController::class, 'cpsGridList']);

        Route::get('cps/generate-report/csv/{id}', [CpsReportcontroller::class, 'exportCpsCsv']);
        Route::get('cps/generate-report/ods/{id}', [CpsReportcontroller::class, 'exportCpsODS']);

        Route::get('activation-reports', [ReportsIndexController::class, 'activationIndex'])->name('activation.report');
        Route::get('activation/save-templates', [ReportsIndexController::class, 'TemplateIndex'])->name('activation.save.template');
        Route::get('save-templates/gridlist', [ReportsIndexController::class, 'activationGridList']);
        Route::get('actvation/generate-report', [ReportsIndexController::class, 'actvationIndex'])->name('activation.generate.report');
        Route::get('generate-report/activation/gridlist', [ReportsIndexController::class, 'actvationGridList']);

        Route::get('activation-report/csv/{id}', [ActivationReportController::class, 'exportCpsCsv']);
    });
});

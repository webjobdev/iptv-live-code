<?php

use Illuminate\Support\Facades\Route;
use Contus\PartnerProgram\Http\Controllers\PartnerProgramController;

Route::prefix('admin')->namespace('Contus\PartnerProgram\Http\Controllers')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('partner-programs', [PartnerProgramController::class, 'index'])->name('partner.index'); // index page
        Route::get('partner-programs/gridlist', [PartnerProgramController::class, 'getGridlist']); // gridlist view

        Route::get('partner-programs/add', [PartnerProgramController::class, 'addPartnerProgram']); // add new partner program
        Route::get('partner-programs/edit/{id}', [PartnerProgramController::class, 'editPartnerProgram']); // edit existing partner program
    });
});

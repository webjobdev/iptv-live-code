<?php

use Contus\Feedback\Api\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\Feedback\Api\Controllers')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('feedback/info', [FeedbackController::class, 'getInfo']);
            Route::post('feedback/add', [FeedbackController::class, 'postAdd']); // add feedback
        });
    });
});

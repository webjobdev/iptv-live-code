<?php

use Contus\Tvshow\Api\Controllers\Admin\SeasonEpisodeControllers;
use Contus\Tvshow\Api\Controllers\Admin\TvshowSeasonControllers;
use Illuminate\Support\Facades\Route;
use Contus\Tvshow\Api\Controllers\Admin\TvShowIndexControllers;

Route::prefix('api/admin')->namespace('Contus\Tvshow\Api\Controllers\Admin')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            // ==========**********==========
            Route::get('tv-show/info', [TvShowIndexControllers::class, 'getInfo']);
            Route::post('create/tv-show', [TvShowIndexControllers::class, 'CreateTvShow']);
            Route::get('tv-show/tvshow-to-edit/{id}', [TvShowIndexControllers::class, 'getTvShowToEdit']);
            Route::post('tv-show/edit/{id}', [TvShowIndexControllers::class, 'postEdit']);
            Route::post('tv-show/records', [TvShowIndexControllers::class, 'postRecords']);
            Route::post('tv-show/fetch/records', [TvShowIndexControllers::class, 'fetchdata']);
            Route::post('tvshow/toggle-publish-now/{id}', [TvShowIndexControllers::class, 'togglePublishNow']);
            Route::post('tv-show/fetch/action', [TvShowIndexControllers::class, 'postAction']);

            Route::post('tv-show/thumbnail', [TvShowIndexControllers::class, 'postThumbnail']);
            Route::post('tv-show/poster', [TvShowIndexControllers::class, 'postPosters']);
            // ==========**********==========

            // ==========**********==========
            Route::post('create/tv-show/season', [TvshowSeasonControllers::class, 'CreateSeason']);
            Route::get('tv-show/season-to-edit/{id}', [TvshowSeasonControllers::class, 'getTvShowToEdit']);
            Route::post('tv-show-season/edit/{id}', [TvshowSeasonControllers::class, 'postEdit']);
            Route::post('remove/season/{id}', [TvshowSeasonControllers::class, 'postRemove']);
            Route::post('tv-show/season/records', [TvshowSeasonControllers::class, 'postRecords']);
            Route::get('tv-show/season/records', [TvshowSeasonControllers::class, 'getRecords']);
            Route::post('tv-show/fetch/season/records', [TvshowSeasonControllers::class, 'fetchRecords']);

            Route::post('tv-show/season/thumbnail', [TvshowSeasonControllers::class, 'postThumbnail']);
            Route::post('tv-show/season/poster', [TvshowSeasonControllers::class, 'postPosters']);
            // ==========**********==========

            // ==========**********==========
            Route::post('create/tv-show/season/episode', [SeasonEpisodeControllers::class, 'CreateEpisode']);
            Route::get('tv-show/season/episode-to-edit/{id}', [SeasonEpisodeControllers::class, 'getEpisodeToEdit']);
            Route::post('tv-show-season/episode/edit/{id}', [SeasonEpisodeControllers::class, 'postEdit']);
            Route::post('episode/delete/{id}', [SeasonEpisodeControllers::class, 'postDelete']);
            Route::post('tv-show/season/episode/toggle-publish-now/{id}', [SeasonEpisodeControllers::class, 'postToggle']);
            Route::post('tv-show/season/episode/records', [SeasonEpisodeControllers::class, 'postRecords']);
            Route::get('tv-show/fetch/season/episode/records', [SeasonEpisodeControllers::class, 'fetchRecords']);
            Route::post('fetch/season/episode/records', [SeasonEpisodeControllers::class, 'fetchEpisodeRecords']);

            Route::post('tv-show/season/episode/thumbnail', [SeasonEpisodeControllers::class, 'postThumbnail']);
            Route::post('tv-show/season/episode/poster', [SeasonEpisodeControllers::class, 'postPosters']);
            // ==========**********==========
        });
    });
});

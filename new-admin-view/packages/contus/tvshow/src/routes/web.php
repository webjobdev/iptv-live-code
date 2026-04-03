<?Php

use Contus\Tvshow\Http\Controllers\Admin\TvShowController;

Route::prefix('admin')->namespace('Contus\Tvshow\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function () {
        Route::get('tvshow', [TvShowController::class, 'index']);

        Route::get('tvshow/gridlist', [TvShowController::class, 'gridList']);
        Route::get('tvshow/add', [TvShowController::class, 'add']);
        Route::get('tvshow/edit-tv-show/{id}', [TvShowController::class, 'editTvShow']);

        Route::get('tvshow/add/season/{id}', [TvShowController::class, 'season'])->name('tvshow.add.season');
        Route::get('tvshow/edit-tv-show-season/season-id/{id}', [TvShowController::class, 'editSeason']);
        
        Route::get('tvshow/season/episode/{id}', [TvShowController::class, 'episode']);
        Route::get('tvshow/season/episode-edit/episode-id/{id}', [TvShowController::class, 'editEpisode']);
    });
});
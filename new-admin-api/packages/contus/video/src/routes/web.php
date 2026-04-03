<?php
use Illuminate\Support\Facades\Route;
use Contus\Video\Http\Controllers\Admin\DashboardController;
use Contus\Video\Http\Controllers\Admin\AnalyticsController;
use Contus\Video\Http\Controllers\Admin\VideoController;
use Contus\Video\Http\Controllers\Admin\CategoryController;
use Contus\Video\Http\Controllers\Admin\CollectionController;
use Contus\Video\Http\Controllers\Admin\GenreController;
use Contus\Video\Http\Controllers\Admin\PresetController;
use Contus\Video\Http\Controllers\Admin\YoutubeImportController;
use Contus\Video\Http\Controllers\Admin\ReportsController;
use Contus\Video\Http\Controllers\Admin\SeasonController;
use Contus\Video\Http\Controllers\Admin\AdsController;
use Contus\Video\Http\Controllers\Admin\CastController;
use Contus\Video\Http\Controllers\Customer\VideoController as CustomerVideoController;

Route::prefix('admin')->middleware(['auth.admin', 'accesslevel'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'getIndex']);
    Route::get('analytics', [AnalyticsController::class, 'getIndex']);

    Route::controller(VideoController::class)->group(function () {
        Route::get('videos', 'getIndex');
        Route::get('videos/add', 'getAdd');
        Route::get('videos/upload_video', 'getVideoUpload');
        Route::get('videos/gridlist', 'getGridlist');
        Route::get('videos/details-video-edit/{id}', 'getDetailsVideoEdit');
        Route::get('videos/view-details-video/{id}', 'getViewDetailsVideo');

        Route::get('livevideos', 'getIndex');
        Route::get('livevideos/gridlist', 'getGridlist');
        Route::get('livevideos/details-video-edit/{id}', 'getDetailsVideoEdit');
        Route::get('livevideos/view-details-video/{id}', 'getViewDetailsVideo');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories', 'getIndex');
        Route::get('categories/gridlist', 'getGridlist');
        Route::get('categories/videos/{id}', 'getVideos');
    });

    Route::controller(CollectionController::class)->group(function () {
        Route::get('collections', 'getIndex');
        Route::get('collections/gridlist', 'getGridlist');
        Route::get('collections/details-video-edit/{id}', 'getDetailsVideoEdit');
    });

    Route::controller(GenreController::class)->group(function () {
        Route::get('genre', 'getIndex');
        Route::get('genre/gridlist', 'getGridlist');
        Route::get('genre/videos/{id}', 'getVideos');
        Route::get('genre/details-video-edit/{id}', 'getDetailsVideoEdit');
    });

    Route::controller(PresetController::class)->group(function () {
        Route::get('presets', 'getIndex');
        Route::get('presets/gridlist', 'getGridlist');
        Route::get('presets/details-video-edit/{id}', 'getDetailsVideoEdit');
    });

    Route::controller(YoutubeImportController::class)->group(function () {
        Route::get('youtube-live', 'getLive');
        Route::get('youtube-import', 'getIndex');
        Route::get('youtube-import/download', 'getDownload');
    });

    Route::get('/reports', [ReportsController::class, 'getIndex']);

    Route::controller(SeasonController::class)->group(function () {
        Route::get('season', 'getIndex');
        Route::get('season/gridlist', 'getGridlist');
    });

    Route::controller(ReportsController::class)->group(function () {
        Route::get('analytics/video', 'getAnalyticsvideo');
        Route::get('analytics/{route}', 'getIndexRoute');
        Route::get('analytics/{route}/gridlist', 'gridlist');
    });

    Route::controller(AdsController::class)->group(function () {
        Route::get('ads', 'getIndex');
        Route::get('ads/gridlist', 'getGridlist');
    });

    Route::controller(CastController::class)->group(function () {
        Route::get('cast', 'getIndex');
        Route::get('cast/gridlist', 'getGridlist');
    });
});

Route::middleware(['xcsrf'])->group(function () {
    Route::controller(CustomerVideoController::class)->group(function () {
        Route::get('livevideos', 'livevideos');
        Route::get('videos', 'index');
        Route::get('video', 'video');
        Route::get('videodetail', 'videodetail');
        Route::get('allvideos', 'allvideos');
        Route::get('listCategories', 'listCategories');
        Route::get('grouplistdetail', 'groupList');
        Route::get('groupvideodetail', 'groupvideodetail');
        Route::get('videodetailsidemenu', 'videodetailsidemenu');
    });
});

Route::middleware(['auth', 'xcsrf'])->group(function () {
    Route::controller(CustomerVideoController::class)->group(function () {
        Route::get('video', 'video');
        Route::get('videodetail', 'videodetail');
    });
});

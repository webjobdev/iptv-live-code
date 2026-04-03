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

use Contus\Video\Http\Controllers\Admin\CategoryController;
use Contus\Video\Http\Controllers\Admin\DashboardController;
use Contus\Video\Http\Controllers\Admin\FeedbackController;
use Contus\Video\Http\Controllers\Admin\RadioCategoryController;
use Contus\Video\Http\Controllers\Admin\SeriesCategoryController;
use Contus\Video\Http\Controllers\Admin\TvCategoryController;
use Contus\Video\Http\Controllers\Admin\VideoController;
use Contus\Video\Http\Controllers\Admin\VodCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('Contus\Video\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get('dashboard', [DashboardController::class, 'getIndex']);
        Route::get('analytics', 'AnalyticsController@getIndex');
    });

    Route::group(['middleware' => []], function () {


        Route::get('videos', 'VideoController@getIndex');
        Route::get('videos/add', [VideoController::class, 'getAdd']);
        Route::get('videos/upload_video', [VideoController::class, 'getVideoUpload']);
        // Route::get('videos/upload_video', [VideoController::class, 'store']);

        // Route::post('api/media/admin/upload-video', [VideoController::class, 'store']);

        Route::get('videos/gridlist', 'VideoController@getGridlist');
        Route::get('videos/details-video-edit/{id}', 'VideoController@getDetailsVideoEdit');
        Route::get('videos/view-details-video/{id}', 'VideoController@getViewDetailsVideo');

        Route::get('livevideos', 'VideoController@getIndex');
        Route::get('livevideos/gridlist', 'VideoController@getGridlist');
        Route::get('livevideos/details-video-edit/{id}', 'VideoController@getLiveVideoEdit');
        Route::get('livevideos/view-details-video/{id}', 'VideoController@getViewDetailsVideo');

        // live event code start
        Route::get('liveevents', [VideoController::class, 'getIndex']);
        Route::get('liveevents/add', [VideoController::class, 'liveeventsAdd']);
        Route::get('liveevents/gridlist', [VideoController::class, 'getLiveeventsGridlist']);
        Route::get('liveevents/view-liveevents-video/{id}', [VideoController::class, 'getViewDetailsVideo']);
        Route::get('liveevents/details-liveevents-edit/{id}', [VideoController::class, 'getDetailsLiveeventsEdit']);
        // live event code end

        Route::get('radio/add', 'VideoController@radioAdd');
        Route::get('radio', 'VideoController@getIndex');
        Route::get('radio/gridlist', 'VideoController@getRadioGridlist');
        Route::get('radio/details-radio-edit/{id}', 'VideoController@getDetailsRadioEdit');
        Route::get('radio/view-radio-video/{id}', 'VideoController@getViewDetailsVideo');

        Route::get('webseries', 'WebseriesController@getIndex');
        Route::get('webseries/gridlist', 'WebseriesController@getGridlist');
        Route::get('webserier/videos/{id}', 'WebseriesController@getVideos');
        Route::get('webseries/add', 'WebseriesController@getAdd');
        Route::get('webseries/edit/{id}', 'WebseriesController@getEdit');

        // categories code start
        Route::get('categories', [CategoryController::class, 'getIndex']);
        Route::get('categories/gridlist', [CategoryController::class, 'getGridlist']);
        Route::get('categories/videos/{id}', [CategoryController::class, 'getVideos']);

        Route::get('radiocategory', 'RadioCategoryController@getIndex');
        Route::get('radiocategory/gridlist', 'RadioCategoryController@getGridlist');
        Route::get('radiocategory/videos/{id}', 'RadioCategoryController@getVideos');

        Route::get('livecategory', 'LiveCategoryController@getIndex');
        Route::get('livecategory/gridlist', 'LiveCategoryController@getGridlist');
        Route::get('livecategory/videos/{id}', 'LiveCategoryController@getVideos');

        Route::get('tvcategory', [TvCategoryController::class, 'getIndex']);
        Route::get('tvcategory/gridlist', [TvCategoryController::class, 'getGridlist']);

        Route::get('vodcategory', [VodCategoryController::class, 'getIndex']);
        Route::get('vodcategory/gridlist', [VodCategoryController::class, 'getGridlist']);

        Route::get('series-category', [SeriesCategoryController::class, 'getIndex']);
        Route::get('series-category/gridlist', [SeriesCategoryController::class, 'getGridlist']);
        // categories code end

        Route::get('collections', 'CollectionController@getIndex');
        Route::get('collections/gridlist', 'CollectionController@getGridlist');
        Route::get('collections/details-video-edit/{id}', 'CollectionController@getDetailsVideoEdit');

        Route::get('genre', 'GenreController@getIndex');
        Route::get('genre/gridlist', 'GenreController@getGridlist');
        Route::get('genre/videos/{id}', 'GenreController@getVideos');
        Route::get('genre/details-video-edit/{id}', 'GenreController@getDetailsVideoEdit');

        Route::get('presets', 'PresetController@getIndex');
        Route::get('presets/gridlist', 'PresetController@getGridlist');
        Route::get('presets/details-video-edit/{id}', 'PresetController@getDetailsVideoEdit');

        Route::get('youtube-live', 'YoutubeImportController@getLive');
        Route::get('youtube-import', 'YoutubeImportController@getIndex');
        Route::get('youtube-import/download', 'YoutubeImportController@getDownload');

        Route::get('/reports', 'ReportsController@getIndex');

        Route::get('season', 'SeasonController@getIndex');
        Route::get('season/gridlist', 'SeasonController@getGridlist');

        Route::get('analytics/video', 'ReportsController@getAnalyticsvideo');
        Route::get('analytics/{route}', 'ReportsController@getIndexRoute');
        Route::get('analytics/{route}/gridlist', 'ReportsController@gridlist');

        Route::get('ads', 'AdsController@getIndex');
        Route::get('cast', 'CastController@getIndex');
        Route::get('ads/gridlist', 'AdsController@getGridlist');
        Route::get('cast/gridlist', 'CastController@getGridlist');

        Route::get('playlists', 'PlaylistsController@getIndex');
        Route::get('playlists/gridlist', 'PlaylistsController@getGridlist');
        Route::get('playlists/videos/{id}', 'PlaylistsController@getVideos');
        Route::get('playlists/details-video-edit/{id}', 'PlaylistsController@getDetailsVideoEdit');
    });
});

Route::group(['namespace' => 'Contus\Video\Http\Controllers\Customer'], function () {
    Route::group(['middleware' => ['xcsrf']], function () {
        Route::get('livevideos', 'VideoController@livevideos');
        Route::get('videos', 'VideoController@index');
        Route::get('video', 'VideoController@video');
        Route::get('videodetail', 'VideoController@videodetail');
        Route::get('allvideos', 'VideoController@allvideos');
        Route::get('listCategories', 'VideoController@listCategories');
        Route::get('grouplistdetail', 'VideoController@groupList');
        Route::get('grouplistdetail', 'VideoController@groupList');
        Route::get('groupvideodetail', 'VideoController@groupvideodetail');
        Route::get('videodetailsidemenu', 'VideoController@videodetailsidemenu');
    });
});

Route::group(['namespace' => 'Contus\Video\Http\Controllers\Customer', 'middleware' => ['auth', 'xcsrf']], function () {
    Route::get('video', 'VideoController@video');
    Route::get('videodetail', 'VideoController@videodetail');
});

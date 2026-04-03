<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

use Contus\Video\Api\Controllers\Admin\DashboardController;
use Contus\Video\Api\Controllers\Admin\LiveeventsController;
use Contus\Video\Api\Controllers\Admin\LiveStreamController;
use Contus\Video\Api\Controllers\Admin\VideoController;
use Contus\Video\Api\Controllers\Admin\SeriesCategoryController;
use Contus\Video\Api\Controllers\Admin\TvCategoryController;
use Contus\Video\Api\Controllers\Admin\VodCategoryController;
use Contus\Video\Api\Controllers\Admin\WebseriesController;
use Contus\Video\Models\TvCategory;
use Contus\Vod\Api\Controllers\Admin\VodIndexController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/admin', 'namespace' => 'Contus\Video\Api\Controllers\Admin'], function () {
    Route::group(['middleware' => 'cors'], function () {
        Route::get('searchbulkexportvideo', 'VideoController@getSearchExportData');
        Route::group(['middleware' => 'jwt-auth'], function () {

            /*Live stream routes start*/
            Route::post('createlivestream', 'LiveStreamController@createlivestream');
            Route::post('startlivestream', 'LiveStreamController@startLiveStream');
            Route::post('stoplivestream', 'LiveStreamController@stopLiveStream');
            Route::post('satuslivestream', 'LiveStreamController@statusLivestream');
            /*Live stream routes end*/

            Route::post('site/language', 'DashboardController@languageChange');
            Route::get('videos/update_video_url', 'VideoController@updateVideosUrl');
            Route::get('dashboard/info', [DashboardController::class, 'getInfo']);
            Route::get('dashboard/getdashboardvideostats', 'DashboardController@getVideoStatistics');
            Route::get('dashboard/signed-customer/{type}', 'DashboardController@getSignedCustomer');
            Route::get('dashboard/subscriber-user/{type}', 'DashboardController@getSubscribedUserData');
            Route::get('dashboard/revenue/{type}', 'DashboardController@getRevenue');
            Route::get('dashboard/revenue_status/{type}', [DashboardController::class, 'getRevenueData']);
            Route::get('dashboard/regionwisevideocount_datefilter/{type}', 'DashboardController@regionWiseVideoCountAnalytics');
            Route::get('dashboard/platformwisevideocount_datefilter/{type}', 'DashboardController@platformWiseVideoCountAnalytics');
            Route::get('dashboard/active-subscriber/{type}', 'DashboardController@getSubscribedUserCount');
            Route::get('dashboard/overviewcount/{type}', [DashboardController::class, 'getOverViewData']);

            Route::get('analytics/getdashboardvideostats', 'AnalyticsController@getVideoStatistics');
            Route::get('analytics/get-top-browsers/{type}', 'AnalyticsController@fetchTopBrowsers');
            Route::get('analytics/get-user-types/{type}', 'AnalyticsController@fetchUserTypes');
            Route::get('analytics/get-total-visitors/{type}', 'AnalyticsController@fetchTotalVisitorsAndPageViews');

            Route::post('image', 'VideoController@uploadImage');
            /* Videos Routes starts */
            Route::get('videos/info', [VideoController::class, 'getInfo']);
            Route::get('indiGeofencing/info/{id}', [VideoController::class, 'getGeoInfo']);
            Route::post('videos/progress', [VideoController::class, 'getProgress']);
            Route::post('videos/records', [VideoController::class, 'postRecords']);
            Route::get('videos/video-to-edit/{id}', [VideoController::class, 'getVideoToEdit']);
            Route::get('videos/video-id/{id}', 'VideoController@getVideoId');
            Route::get('videos/video-categories/{id}', 'VideoController@getEdit');
            Route::post('videos/update-status', [VideoController::class, 'postUpdateStatus']);
            Route::post('videos/edit/{id}', [VideoController::class, 'postEdit']);
            Route::post('videos/addLanguage/{id}', 'VideoController@addLanguage');
            Route::post('videos/multiple/addLanguage/{id}', 'VideoController@addLanguageToMultipleVideos');
            // Route::post('liveevents/toggle-publish-now/{id}', [VideoController::class, 'togglePublishNow']);

            Route::post('videos/delete-action', 'VideoController@postDeleteAction');
            Route::post('videos/bulk-update-status', 'VideoController@postBulkUpdateStatus');
            Route::post('videos/thumbnail', 'VideoController@postThumbnail');
            Route::post('videos/poster', 'VideoController@postPosters');
            Route::post('videos/mobileposter', 'VideoController@postMobilePosters');
            Route::get('videos/complete-video-details/{id}', 'VideoController@getCompleteVideoDetails');
            Route::post('videos/handle-fine-uploader', [VideoController::class, 'postHandleFineUploader']);
            Route::post('videos/add', [VideoController::class, 'postAdd']);
            Route::post('new/videos/add', [VideoController::class, 'Add']);
            Route::post('videos/delete-thumbnail/{id}', 'VideoController@postDeleteThumbnail');
            Route::post('videos/subtitle', 'VideoController@postSubtitle');
            Route::post('videos/uplaod-banner-video', 'VideoController@postUplaodBannerVideo');
            Route::post('videos/upload-subtitles', 'VideoController@postUploadSubTitles');
            Route::post('videos/delete-subtitle/{id}', 'VideoController@deleteSubtitle');
            Route::get('generate_sprite', 'VideoController@generateSprite');
            Route::post('videos/metadata', 'VideoController@generateMetaData');
            Route::post('videos/performancestatistics', 'VideoController@getvideoPerformanceStatistics');
            Route::post('videos/geographicstatistics', 'VideoController@getvideoGeographicStatistics');
            Route::post('videos/headerprogress', 'VideoController@getHeaderVideoProgress');
            Route::post('videos/transcode-status/{id}', 'VideoController@postTranscodeStatus');
            Route::post('livevideos/update-status', 'VideoController@postUpdateStatus');
            Route::post('videos/edit-video/{id}', 'VideoController@postEditVideo');
            Route::post('videos/upload-audios', 'VideoController@postUploadAudio');
            Route::post('videos/upload-trailer', 'VideoController@postUploadTrailer');
            Route::post('videos/delete-audiotrack/{id}', 'VideoController@deleteLingualAudioTrack');
            /* Videos Routes ends */

            /*Live videos Routes start*/
            Route::get('livevideos/info', 'VideoController@getInfo');
            Route::post('livevideos/records', 'LivevideoController@postRecords');

            // live events

            Route::get('liveevents/info', 'VideoController@getInfo');
            Route::post('liveevents/records', [LiveeventsController::class, 'postRecords']);
            Route::post('liveevents/update-status', 'VideoController@postUpdateStatus');
            Route::post('createevent', [LiveStreamController::class, 'liveevent']);
            Route::post('liveevents/toggle-publish-now/{id}', [LiveStreamController::class, 'togglePublishNow']);


            Route::get('radio/info', 'VideoController@getInfo');
            Route::post('radio/records', 'RadioController@postRecords');
            Route::post('radio/update-status', 'VideoController@postUpdateStatus');
            Route::post('createradio', 'LiveStreamController@createradio');
            /*Live videos Routes end*/

            /* Webseries Routes starting*/
            Route::get('webseries/info', 'WebseriesController@getInfo');
            Route::post('webseries/records', 'WebseriesController@postRecords');
            Route::post('webseries/update-status', 'WebseriesController@postBulkUpdateStatus');
            Route::get('webseries/updated-details', 'WebseriesController@getUpdatedDetails');
            Route::post('webseries/action', 'WebseriesController@postAction');
            Route::post('webseries/add', [WebseriesController::class, 'postAdd']);
            Route::post('webseries/thumbnail', 'WebseriesController@postThumbnail');
            Route::post('webseries/poster', 'WebseriesController@postPosters');
            Route::post('webseries/edit/{id}', 'WebseriesController@postEdit');
            Route::get('webseries/edit-view/{id}', 'WebseriesController@getWebseriesEdit');
            Route::post('webseries/addLanguage/{id}', 'WebseriesController@addLanguage');
            /*Webseries Routes end*/

            /* Category Routes starting*/
            Route::get('categories/info', 'CategoryController@getInfo');
            Route::get('webcategories', 'VideoController@getWebSeriesCategoryInfo');
            Route::post('categories/records', 'CategoryController@postRecords');
            Route::get('categories/videos/{id}', 'CategoryController@getVideoToEdit');
            Route::post('categories/parent-category/{id}', 'CategoryController@postParentCategory');
            Route::get('categories/video-categories/{id?}', 'CategoryController@getVideoCategories');
            Route::get('categories/updated-details', 'CategoryController@getUpdatedDetails');
            Route::post('categories/update-status', 'CategoryController@postUpdateStatus');
            Route::post('categories/edit/{id}', 'CategoryController@postEdit');
            Route::post('categories/action', 'CategoryController@postAction');
            Route::post('categories/bulk-update-status', 'CategoryController@postBulkUpdateStatus');
            Route::post('categories/add', 'CategoryController@postAdd');
            Route::post('categories/category-image', 'CategoryController@postCategoryImage');
            Route::post('categories/mobile-category-image', 'CategoryController@postMobileCategoryImage');
            Route::post('categories/banner-image', 'CategoryController@postBannerImage');
            Route::post('categories/delete-category-image/{id}', 'CategoryController@postDeleteCategoryImage');
            // Route::post('season/delete-season-image/{id}', 'CategoryController@postDeleteSeasonImage');
            Route::post('season/delete-season-image/{id}', 'SeasonController@postDeleteSeasonImage');
            Route::post('categories/addLanguage/{id}', 'CategoryController@addLanguage');
            Route::post('categories/delete-action', 'CategoryController@postDeleteAction');

            // ==========**********==========
            // tv category
            // ==========**********==========

            Route::get('tvcategory/info', [TvCategoryController::class, 'getInfo']);
            Route::post('tvcategory/fetch/records', [TvCategoryController::class, 'fetchRecords']);
            Route::post('tvcategory/add', [TvCategoryController::class, 'postAdd']);
            Route::post('tv-category/edit/category/{id}', [TvCategoryController::class, 'postEditCategory']);
            Route::post('tv-category/add/category', [TvCategoryController::class, 'postAddCategory']);
            Route::post('tvcategory/categories/{id}', [TvCategoryController::class, 'getCategoryToEdit']);
            Route::post('tv-category/add/channel', [TvCategoryController::class, 'postAddChannel']);
            Route::post('tvcategory/fetch/action', [TvCategoryController::class, 'postAction']);
            Route::post('tv-category/delete-category/{id}', [TvCategoryController::class, 'postDeleteCategory']);
            Route::post('tv-category/delete-channel/{id}', [TvCategoryController::class, 'postDeleteChannel']);

            // ==========**********==========
            // vod category
            // ==========**********==========

            Route::get('vod-category/info', [VodCategoryController::class, 'getInfo']);
            Route::post('vod-category/fetch/records', [VodCategoryController::class, 'fetchRecords']);
            Route::post('vod-category/add', [VodCategoryController::class, 'postAdd']);
            Route::post('vod-category/edit/categorie/{id}', [VodCategoryController::class, 'postCategoryEdit']);
            Route::post('vod-category/add/categorie', [VodCategoryController::class, 'postAddCategory']);
            Route::post('vod-category/edit/categories/{id}', [VodCategoryController::class, 'getCategoryToEdit']);
            Route::post('vod-category/add/sub-categorie', [VodCategoryController::class, 'addSubCategory']);
            Route::post('vod-category/fetch/action', [VodCategoryController::class, 'postAction']);
            Route::post('vod-category/delete-category/{id}', [VodCategoryController::class, 'postDeleteCategory']);
            Route::post('vod-category/delete-sub-category/{id}', [VodCategoryController::class, 'postDeleteSubCtgry']);
            Route::get('vod-category/get/records', [VodCategoryController::class, 'getRecords']);

            // ==========**********==========
            // series category
            // ==========**********==========

            Route::get('series-category/info', [SeriesCategoryController::class, 'getInfo']);
            Route::post('series-category/fetch/records', [SeriesCategoryController::class, 'fetchRecords']);
            Route::get('series-category/get/records', [SeriesCategoryController::class, 'getRecords']);

            Route::post('series-category/add', [SeriesCategoryController::class, 'postAdd']);
            Route::post('series-category/edit/categorie/{id}', [SeriesCategoryController::class, 'postCategoryEdit']);

            Route::post('series-category/add/categorie', [SeriesCategoryController::class, 'postAddCategory']);
            Route::post('series-category/edit/categories/{id}', [SeriesCategoryController::class, 'getCategoryToEdit']);

            Route::post('series-category/add/sub-categorie', [SeriesCategoryController::class, 'addSubCategory']);
            Route::post('series-category/fetch/action', [SeriesCategoryController::class, 'postAction']);

            Route::post('series-category/delete-category/{id}', [SeriesCategoryController::class, 'postDeleteCategory']);
            Route::post('series-category/delete-sub-category/{id}', [SeriesCategoryController::class, 'postDeleteSubCtgry']);


            /* Category Routes ending*/

            /* Radio Category Routes starting*/
            Route::get('radiocategory/info', 'RadioCategoryController@getInfo');
            Route::post('radiocategory/records', 'RadioCategoryController@postRecords');
            Route::get('radiocategory/videos/{id}', 'RadioCategoryController@getVideoToEdit');
            Route::post('radiocategory/parent-category/{id}', 'RadioCategoryController@postParentCategory');
            Route::get('radiocategory/video-categories/{id?}', 'RadioCategoryController@getVideoCategories');
            Route::get('radiocategory/updated-details', 'RadioCategoryController@getUpdatedDetails');
            Route::post('radiocategory/update-status', 'RadioCategoryController@postUpdateStatus');
            Route::post('radiocategory/edit/{id}', 'RadioCategoryController@postEdit');
            Route::post('radiocategory/action', 'RadioCategoryController@postAction');
            Route::post('radiocategory/bulk-update-status', 'RadioCategoryController@postBulkUpdateStatus');
            Route::post('radiocategory/add', 'RadioCategoryController@postAdd');
            Route::post('radiocategory/category-image', 'RadioCategoryController@postCategoryImage');
            Route::post('radiocategory/delete-category-image/{id}', 'RadioCategoryController@postDeleteCategoryImage');
            Route::post('radiocategory/addLanguage/{id}', 'RadioCategoryController@addLanguage');
            Route::post('radiocategory/delete-action', 'RadioCategoryController@postDeleteAction');
            /* Radio Category Routes ending*/

            /* Live Category Routes starting*/
            Route::get('livecategory/info', 'LiveCategoryController@getInfo');
            Route::post('livecategory/records', 'LiveCategoryController@postRecords');
            Route::get('livecategory/videos/{id}', 'LiveCategoryController@getVideoToEdit');
            Route::post('livecategory/parent-category/{id}', 'LiveCategoryController@postParentCategory');
            Route::get('livecategory/video-categories/{id?}', 'LiveCategoryController@getVideoCategories');
            Route::get('livecategory/updated-details', 'LiveCategoryController@getUpdatedDetails');
            Route::post('livecategory/update-status', 'LiveCategoryController@postUpdateStatus');
            Route::post('livecategory/edit/{id}', 'LiveCategoryController@postEdit');
            Route::post('livecategory/action', 'LiveCategoryController@postAction');
            Route::post('livecategory/bulk-update-status', 'LiveCategoryController@postBulkUpdateStatus');
            Route::post('livecategory/add', 'LiveCategoryController@postAdd');
            Route::post('livecategory/category-image', 'LiveCategoryController@postCategoryImage');
            Route::post('livecategory/delete-category-image/{id}', 'LiveCategoryController@postDeleteCategoryImage');
            Route::post('livecategory/addLanguage/{id}', 'LiveCategoryController@addLanguage');
            Route::post('livecategory/delete-action', 'LiveCategoryController@postDeleteAction');
            /* Live Category Routes ending*/

            /*Genre Routes Starts*/
            Route::get('collections/info', 'CollectionController@getInfo');
            Route::post('collections/records', 'CollectionController@postRecords');
            Route::get('collections/video-to-edit/{id}', 'CollectionController@getVideoToEdit');
            Route::post('collections/update-status', 'CollectionController@postUpdateStatus');
            Route::post('collections/edit/{id}', 'CollectionController@postEdit');
            Route::post('collections/action', 'CollectionController@postAction');
            Route::post('collections/bulk-update-status', 'CollectionController@postBulkUpdateStatus');
            Route::post('collections/create-collection', 'CollectionController@postCreateCollection');
            /*Genre Routes End*/

            /*Sub genre Routes Start*/
            Route::get('genre/info', 'GenreController@getInfo');
            Route::post('genre/add', 'GenreController@postAdd');
            Route::post('genre/records', 'GenreController@postRecords');
            Route::get('genre/video-to-edit/{id}', 'GenreController@getVideoToEdit');
            Route::post('genre/update-status', 'GenreController@postUpdateStatus');
            Route::post('genre/edit/{id}', 'GenreController@postEdit');
            Route::post('genre/action', 'GenreController@postAction');
            Route::post('genre/bulk-update-status', 'GenreController@postBulkUpdateStatus');
            Route::get('genre/videos/{id}', 'GenreController@getVideoCollections');
            Route::post('genre/addLanguage/{id}', 'GenreController@addLanguage');
            /*Sub genre Routes End*/

            /*Sub seasons Routes Start*/
            Route::get('season/info', 'SeasonController@getInfo');
            Route::post('season/records', 'SeasonController@postRecords');
            Route::post('season/update-status', 'SeasonController@postUpdateStatus');
            Route::post('season/action', 'SeasonController@postAction');
            Route::post('season/add', 'SeasonController@postAdd');
            Route::post('season/edit/{id}', 'SeasonController@postEdit');
            Route::post('season/addLanguage/{id}', 'SeasonController@addLanguage');
            Route::post('season/bulk-update-status', 'SeasonController@postBulkUpdateStatus');
            /*Sub seasons Routes End*/

            /*Presets Routes Start*/
            Route::get('presets/info', 'PresetController@getInfo');
            Route::get('presets/get-presets', 'PresetController@getPresets');
            Route::post('presets/records', 'PresetController@postRecords');
            Route::get('presets/video-to-edit/{id}', 'PresetController@getVideoToEdit');
            Route::post('presets/update-status', 'PresetController@postUpdateStatus');
            Route::post('presets/edit/{id}', 'PresetController@postEdit');
            Route::post('presets/delete-action', 'PresetController@postDeleteAction');
            Route::post('presets/bulk-update-status', 'PresetController@postBulkUpdateStatus');
            /*Presets Routes End*/

            /*Comments Routes Start*/
            Route::get('comments/info', 'CommentsController@getInfo');
            Route::post('comments/records', 'CommentsController@postRecords');
            Route::get('comments/video-to-edit/{id}', 'CommentsController@getVideoToEdit');
            Route::post('comments/updatestatus/{id}', 'CommentsController@postUpdateStatus');
            Route::post('comments/update-status/{id}', 'CommentsController@postUpdateStatus');
            Route::post('comments/edit/{id}', 'CommentsController@postEdit');
            Route::post('comments/delete-action', 'CommentsController@postDeleteAction');
            Route::post('comments/bulk-update-status', 'CommentsController@postBulkUpdateStatus');

            Route::post('getvideoComments', 'CommentsController@getVideocomments');
            Route::post('getreplyvideoComments', 'CommentsController@getreplyvideoComments');
            Route::post('getallreplyvideoComments', 'CommentsController@getallreplyvideoComments');
            /*Comments Routes End*/

            /*Analytics/Reports Routes Start*/
            Route::post('most_commented/analytics/records', 'Reports\MostCommentedReportsController@postRecords');
            Route::post('most_favourite/analytics/records', 'Reports\MostFavouriteReportsController@postRecords');
            Route::post('top_category/analytics/records', 'Reports\TopCategoriesReportsController@postRecords');
            Route::post('region_wise_view/analytics/records', 'Reports\RegionwiseViewReportsController@postRecords');
            Route::post('most_viewed/analytics/records', 'Reports\MostViewedReportsController@postRecords');
            /*Analytics/Reports Routes End*/
            Route::get('videos/update_video_url', 'VideoController@updateVideosUrl');

            /* Category Routes starting*/
            Route::get('ads/info', 'AdsController@getInfo');
            Route::post('ads/records', 'AdsController@postRecords');
            Route::get('ads/videos/{id}', 'AdsController@getVideoToEdit');
            Route::post('ads/parent-category/{id}', 'AdsController@postParentCategory');
            Route::get('ads/video-adds/{id?}', 'AdsController@getVideoAds');
            Route::get('ads/updated-details', 'AdsController@getUpdatedDetails');
            Route::post('ads/update-status', 'AdsController@postUpdateStatus');
            Route::post('ads/edit/{id}', 'AdsController@postEdit');
            Route::post('ads/action', 'AdsController@postAction');
            Route::post('ads/bulk-update-status', 'AdsController@postBulkUpdateStatus');
            Route::post('ads/add', 'AdsController@postAdd');
            Route::post('ads/upload', 'AdsController@postCategoryImage');
            Route::post('ads/delete-category-image/{id}', 'AdsController@postDeleteCategoryImage');
            Route::post('ads/addLanguage/{id}', 'AdsController@addLanguage');
            /* Category Routes ending*/

            Route::post('cast/add', 'CastController@postAdd');
            Route::post('cast/edit/{id}', 'CastController@postAdd');
            Route::post('cast/cast-image', 'CastController@postCastImage');
            Route::post('cast/records', 'CastController@postRecords');
            Route::get('cast/info', 'CastController@getInfo');
            Route::post('cast/bulk-update-status', 'CastController@postBulkUpdateStatus');
            Route::post('cast/action', 'CastController@postAction');
            Route::post('cast/update-status', 'CastController@postUpdateStatus');
            Route::get('cast/searchcast', 'CastController@searchCast');
            Route::get('cast/searchvideos', 'CastController@searchVideos');

            Route::get('fetchaccess', 'VideoController@handleAccessLevel');

            /** Playlists Routes */
            Route::get('videos/playlists/info', 'PlaylistController@getInfo');
            Route::post('videos/playlists/records', 'PlaylistController@postRecords');
            Route::post('videos/playlists/update-status', 'PlaylistController@postUpdateStatus');
            Route::post('videos/playlists/playlist-image/{module}', 'AudioBaseController@postUploadThumbnail');
            Route::post('videos/playlists/add', 'PlaylistController@postAdd');
            Route::post('videos/playlists/edit/{id}', 'PlaylistController@postEdit');
            ROute::post('videos/playlists/addvideos/{id}', 'PlaylistController@addVideostoPlaylist');
            Route::post('videos/playlists/action', 'PlaylistController@postAction');
            Route::post('videos/playlists/delete-artist-image/{id}', 'PlaylistController@postDeletePlaylistImage');
            Route::post('videos/playlist-videos', 'PlaylistController@playlistVideos');
            // Route::get('videos/playlists/audio-artists/{id?}', 'PlaylistController@getAudioPlaylists');
            Route::post('videos/playlists/delete-action', 'PlaylistController@postDeleteAction');
            Route::get('videos/playlists/searchPlaylist', 'PlaylistController@searchPlaylist');
            Route::get('videos/playlists/allPlaylists', 'PlaylistController@getAllPlaylists');
            Route::get('videos/playlists/playlist-videos/{id?}', 'PlaylistController@getPlaylistVideos');
            Route::post('playlists/delete-action', 'PlaylistController@postviewDeleteAction');
            Route::get('videos/playlist/searchvideos', 'PlaylistController@searchVideos');

            Route::post('playlist/addLanguage/{id}', 'PlaylistController@addLanguage');

            // ========================================*******************************========================================
            Route::post('dashboard/available-content/fetchrecords', [DashboardController::class, 'fetchContent']);
            Route::post('dashboard/subscriber-count/fetchrecords', [DashboardController::class, 'fetchSubCount']);
            Route::post('dashboard/streams/fetchrecords', [DashboardController::class, 'fetchstreams']);
            Route::post('dashboard/epg/fetchrecords', [DashboardController::class, 'fetchepg']);
            Route::post('dashboard/subscriber-device-count/records', [DashboardController::class, 'fetchsubDevice']);
            Route::post('dashboard/subscriber-active-count/records', [DashboardController::class, 'fetchActiveCount']);
            Route::post('dashboard/device-count/records', [DashboardController::class, 'fetchDeviceCount']);
            Route::post('dashboard/total-sales-revenue/records', [DashboardController::class, 'fetchTotalRevenue']);
            Route::post('dashboard/total-payment-type-sales-revenue/records', [DashboardController::class, 'fetchpyt']);
            Route::post('dashboard/sales-revenue-currency/records', [DashboardController::class, 'fetchcurrency']);
            // ========================================*******************************========================================

        });
    });
});

Route::post('media/upload-video', [VideoController::class, 'store']);

Route::group(['middleware' => ['cors']], function () {
    Route::get('health-check', function () {
        return 'Admin API service is up!';
    });
});

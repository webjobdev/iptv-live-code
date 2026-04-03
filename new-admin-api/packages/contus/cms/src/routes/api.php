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

Route::group(['prefix' => 'api/admin/cms', 'namespace' => 'Contus\Cms\Api\Controllers\Cms'], function () {

    Route::group(['middleware' => ['api.admin', 'accesslevel']], function () {
        Route::resource('email', 'EmailResourceController');
        Route::resource('sms', 'SmsResourceController');
        Route::resource('static', 'StaticResourceController');
        Route::resource('latestnews', 'LatestNewsResourceController');
        Route::resource('contactus', 'ContactusController');
    });
});

Route::group(['prefix' => 'api/admin', 'namespace' => 'Contus\Cms\Api\Controllers\Cms'], function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {
            /** EmailContent Route */
            Route::get('emails/info', 'EmailController@getInfo');
            Route::post('emails/records', 'EmailController@postRecords');
            Route::get('emails/email-data/{id}', 'EmailController@getEmailData');
            Route::post('emails/edit/{id}', 'EmailController@postEdit');
            Route::post('emails/addLanguage/{id}', 'EmailController@addLanguage');

            /** StaticContent Route */
            Route::get('static-content/info', 'StaticContentController@getInfo');
            Route::post('static-content/records', 'StaticContentController@postRecords');
            Route::get('static-content/static-data/{id}', 'StaticContentController@getStaticData');
            Route::post('static-content/add', 'StaticContentController@postAdd');
            Route::post('static-content/edit/{id}', 'StaticContentController@postEdit');
            Route::post('static-content/addLanguage/{id}', 'StaticContentController@addLanguage');
            Route::post('static-content/action', 'StaticContentController@postAction');
            Route::post('static-content/update-menu/{id}', 'StaticContentController@postFooterMenu');
            Route::post('static/bulkstatusfooter', 'StaticContentController@postBulkFooterStatus');

            /** BannerContent Route */
            Route::get('banner/info', 'BannerController@getInfo');
            Route::post('banner/records', 'BannerController@postRecords');
            Route::post('banner/banner-image', 'BannerController@postBannerImage');
            Route::post('banner/mobile-banner-image', 'BannerController@postMobileBannerImage');
            Route::post('banner/delete-banner-image/{id}', 'BannerController@postDeleteBannerImage');
            Route::post('banner/add', 'BannerController@postAdd');
            Route::post('banner/edit/{id}', 'BannerController@postEdit');
            Route::get('banner/searchvideos', 'BannerController@searchVideos');
            Route::post('banner/action', 'BannerController@postAction');

            /** kids banner Route */
            Route::get('kidsbanner/info', 'KidsBannerController@getInfo');
            Route::post('kidsbanner/records', 'KidsBannerController@postRecords');
            Route::post('kidsbanner/banner-image', 'KidsBannerController@postBannerImage');
            Route::post('kidsbanner/mobile-banner-image', 'KidsBannerController@postMobileBannerImage');
            Route::post('kidsbanner/delete-banner-image/{id}', 'KidsBannerController@postDeleteBannerImage');
            Route::post('kidsbanner/add', 'KidsBannerController@postAdd');
            Route::post('kidsbanner/edit/{id}', 'KidsBannerController@postEdit');
            Route::get('kidsbanner/searchvideos', 'KidsBannerController@searchVideos');
            Route::post('kidsbanner/action', 'KidsBannerController@postAction');

            /** Landingpage banner Route */
            Route::get('landingbanner/info', 'LandingBannerController@getInfo');
            Route::post('landingbanner/records', 'LandingBannerController@postRecords');
            Route::post('landingbanner/banner-image', 'LandingBannerController@postBannerImage');
            Route::post('landingbanner/mobile-banner-image', 'LandingBannerController@postMobileBannerImage');
            Route::post('landingbanner/delete-banner-image/{id}', 'LandingBannerController@postDeleteBannerImage');
            Route::post('landingbanner/add', 'LandingBannerController@postAdd');
            Route::post('landingbanner/edit/{id}', 'LandingBannerController@postEdit');
            Route::get('landingbanner/searchvideos', 'LandingBannerController@searchVideos');
            Route::post('landingbanner/action', 'LandingBannerController@postAction');

            /** Home and footer banner Route */
            Route::get('home-footer-banner/info', 'HomefooterBannerController@getInfo');
            Route::post('home-footer-banner/records', 'HomefooterBannerController@postRecords');
            Route::post('home-footer-banner/banner-image', 'HomefooterBannerController@postBannerImage');
            Route::post('home-footer-banner/mobile-banner-image', 'HomefooterBannerController@postMobileBannerImage');
            Route::post('home-footer-banner/delete-banner-image/{id}', 'HomefooterBannerController@postDeleteBannerImage');
            Route::post('home-footer-banner/add', 'HomefooterBannerController@postAdd');
            Route::post('home-footer-banner/edit/{id}', 'HomefooterBannerController@postEdit');
            Route::get('home-footer-banner/searchvideos', 'HomefooterBannerController@searchVideos');
            Route::post('home-footer-banner/action', 'HomefooterBannerController@postAction');
            

            /** live banner Route */
            Route::get('livebanner/info', 'LiveBannerController@getInfo');
            Route::post('livebanner/records', 'LiveBannerController@postRecords');
            Route::post('livebanner/banner-image', 'LiveBannerController@postBannerImage');
            Route::post('livebanner/mobile-banner-image', 'LiveBannerController@postMobileBannerImage');
            Route::post('livebanner/delete-banner-image/{id}', 'LiveBannerController@postDeleteBannerImage');
            Route::post('livebanner/add', 'LiveBannerController@postAdd');
            Route::post('livebanner/edit/{id}', 'LiveBannerController@postEdit');
            Route::get('livebanner/searchvideos', 'LiveBannerController@searchVideos');
            Route::post('livebanner/action', 'LiveBannerController@postAction');
                        

            /** BannerContent Route */
            // Route::get('live_banner/info', 'LiveBannerController@getInfo');
            // Route::post('live_banner/records', 'LiveBannerController@postRecords');
            // Route::post('live_banner/banner-image', 'LiveBannerController@postBannerImage');
            // Route::post('live_banner/delete-banner-image/{id}', 'LiveBannerController@postDeleteBannerImage');
            // Route::post('live_banner/add', 'LiveBannerController@postEdit');
            // Route::post('live_banner/edit/{id}', 'LiveBannerController@postEdit');
            // Route::get('live_banner/searchvideos', 'LiveBannerController@searchVideos');
            // Route::post('live_banner/action', 'LiveBannerController@postAction');

            // Route::get('recommend/searchvideos', 'LiveBannerController@recommendsearchVideos');
        });
    });
});

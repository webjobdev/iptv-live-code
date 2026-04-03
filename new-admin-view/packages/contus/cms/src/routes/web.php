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


 Route::group ( [ 'prefix' => 'admin','namespace' => 'Contus\Cms\Http\Controllers\Admin' ], function () {
  
  Route::group ( [ 'middleware' => [ ] ], function ()  {

  /** EmailContent Route **/
  Route::get('emails', 'EmailController@getIndex' );
  Route::get('emails/gridlist', 'EmailController@getGridlist');
  Route::get('emails/details-email-edit/{id}', 'EmailController@getDetailsEmailEdit');
  Route::get('emails', 'EmailController@getIndex' );
  Route::get('emails/gridlist', 'EmailController@getGridlist');

  /** StaticContent Route **/
  Route::get('static-content', 'StaticContentController@getIndex' );
  Route::get('static-content/gridlist', 'StaticContentController@getGridlist');
  Route::get('static-content/add-static-content', 'StaticContentController@addStaticContent');
  Route::get('static-content/edit-static-content/{id}', 'StaticContentController@getEditStaticContent');

  /** BannerContent Route **/
  Route::get('banner', 'BannerController@getIndex' );
  Route::get('banner/gridlist', 'BannerController@getGridlist');
  Route::post ( 'banner/banner-image', 'BannerController@postBannerImage' );
  Route::post ( 'banner/edit', 'BannerController@postEdit' );

    /** Kids banner Route **/
    Route::get('kidsbanner', 'KidsBannerController@getIndex' );
    Route::get('kidsbanner/gridlist', 'KidsBannerController@getGridlist');
    Route::post ( 'kidsbanner/banner-image', 'KidsBannerController@postBannerImage' );
    Route::post ( 'kidsbanner/edit', 'KidsBannerController@postEdit' );

        /** Laningpage banner Route **/
        Route::get('landingbanner', 'LandingBannerController@getIndex' );
        Route::get('landingbanner/gridlist', 'LandingBannerController@getGridlist');
        Route::post ( 'landingbanner/mobile-banner-image', 'LandingBannerController@postMobileBannerImage' );
        Route::post ( 'landingbanner/edit', 'LandingBannerController@postEdit' );


        /** home and footer banner Route **/
        Route::get('home-footer-banner', 'HomeFooterBannerController@getIndex' );
        Route::get('home-footer-banner/gridlist', 'HomeFooterBannerController@getGridlist');
        Route::post ( 'home-footer-banner/mobile-banner-image', 'HomeFooterBannerController@postMobileBannerImage' );
        Route::post ( 'home-footer-banner/edit', 'HomeFooterBannerController@postEdit' );

        /** live banner Route **/
        Route::get('livebanner', 'LiveBannerController@getIndex' );
        Route::get('livebanner/gridlist', 'LiveBannerController@getGridlist');
        Route::post ( 'livebanner/mobile-banner-image', 'LiveBannerController@postMobileBannerImage' );
        Route::post ( 'livebanner/edit', 'LiveBannerController@postEdit' );

  } );
 } );
   

Route::group(['middleware' => ['tokenauth', 'xcsrf']], function () {
    Route::group(['namespace' => 'Contus\Cms\Http\Controllers\Customer'], function () {
        Route::get('staticContentTemplate', 'StaticContentController@getStaticcontent');
    });
});
Route::group(['middleware' => ['tokenauth']], function () {
    Route::group(['namespace' => 'Contus\Cms\Http\Controllers\Customer'], function () {
        Route::get('static/{slug}', 'StaticContentController@getStaticFullContent');
    });
});
Route::group(['namespace' => 'Contus\Cms\Http\Controllers\Customer'], function () {
    Route::group(['middleware' => ['xcsrf']], function () {
        Route::get('blog', 'LatestNewsController@getBlog');
        Route::get('blogdetail', 'LatestNewsController@getBlogDetail');
    });
});


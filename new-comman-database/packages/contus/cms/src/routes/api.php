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

Route::group(['prefix' => 'common/api/v2', 'namespace' => 'Contus\Cms\Api\Controllers\Staticcontent'], function () {
    Route::group(['middleware' => ['cors', 'updatedversion']], function () {
        Route::get('staticcontent/{slug}', 'StaticContentController@getStaticContent')->middleware('cacheable');
    });
});
Route::group(['prefix' => 'common/api/v2', 'namespace' => 'Contus\Cms\Api\Controllers\Staticcontent'], function () {
    Route::group(['middleware' => ['cors', 'updatedversion', 'jwt-auth:1']], function () {
        Route::get('footer', 'StaticContentController@getSiteFooter')->middleware('cacheable');
        Route::get('menu-categories', 'StaticContentController@getMenuCategories')->middleware('cacheable');
    });
});

Route::group(['prefix' => 'common/api/v2', 'namespace' => 'Contus\Cms\Api\Controllers\Cms'], function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::post('staticContent/contactus', 'ContactusController@postContact');
    });
});

Route::group(['middleware' => ['cors']], function () {
    Route::get('health-check', function () {
        return 'Common service is up!';
    });
});

<?php

use Contus\Geofencing\Api\Controllers\Admin\CountryRegionController;
use Illuminate\Http\Request;

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
  Route::prefix('api/admin')->namespace('Contus\Geofencing\Api\Controllers\Admin')->group( function () {
    Route::group ( [ 'middleware' => [ 'cors' ] ], function ()  {
        Route::group(['middleware' => 'jwt-auth'], function () {
            Route::get('geo-countries', 'CountryRegionController@getCountries');
            Route::get('geo-regions/{id}', 'CountryRegionController@getRegions');
            Route::post('geo-regions-details', 'CountryRegionController@getRegionsDetail');
            Route::get('geofencing/info', 'CountryRegionController@getInfo');
            Route::post('add-geo-settings', [CountryRegionController::class, 'getSettingType']);
            Route::post('add-global-geo-settings', 'CountryRegionController@getGlobalSettings');
        });
    });
  });


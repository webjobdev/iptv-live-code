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

 Route::prefix('admin')->namespace('Contus\Geofencing\Http\Controllers\Admin')->group( function() {
   Route::group ( [ 'middleware' => [ 'auth.admin','accesslevel'] ], function ()  {
    Route::get('geo-management', 'CountriesController@getIndex');
   } );
 });
 

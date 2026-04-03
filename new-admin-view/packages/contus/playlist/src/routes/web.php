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
Route::prefix('admin')->namespace('Contus\Playlist\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function () {        
        Route::get('audios/{route}', 'AudioBaseController@getIndex');
        Route::get('audios/{route}/add', 'AudioBaseController@getAdd');
        Route::get('audios/{route}/gridlist', 'AudioBaseController@getGridlist');
        Route::get('audios/{route}/edit/{id}', 'AudioBaseController@getEdit');
        Route::get('audios/details-audio-edit/{id}', 'AudioController@getDetailsAudioEdit');
        Route::get('audios/view-details-audio/{id}', 'AudioController@getViewDetailsAudio');
        Route::get('albums/audios/{id}', 'AlbumController@getAlbumAudios');
        Route::get('artists/audios/{id}', 'ArtistController@getAritstAudios');
    });
});
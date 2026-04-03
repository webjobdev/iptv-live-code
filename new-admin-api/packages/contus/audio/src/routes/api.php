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
Route::group(['prefix' => 'api/admin', 'namespace' => 'Contus\Audio\Api\Controllers\Admin'], function () {
    Route::group(['middleware' => 'cors'], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {
            /** Album Routes */
            Route::get('albums/info', 'AlbumController@getInfo');
            Route::post('albums/records', 'AlbumController@postRecords');
            Route::post('albums/add', 'AlbumController@postAdd');

            Route::post('audio-base/thumbnail', 'AudioBaseController@postUploadThumbnail');
            Route::post('albums/update-status', 'AlbumController@postUpdateStatus');
            Route::post('albums/action', 'AlbumController@postAction');
            Route::get('albums/album-edit/{id}', 'AlbumController@getAlbumToEdit');
            Route::post('albums/edit/{id}', 'AlbumController@postEdit');
            Route::post('album/audio-update', 'AlbumController@postAudioBulkUpdate');
            Route::get('albums/audio-albums/{id?}', 'AlbumController@getAudioAlbums');

            /** Audio Routes */
            Route::post('audios/handle-fine-uploader', 'AudioController@postHandleFineUploader');
            Route::get('audios/info', 'AudioController@getInfo');
            Route::post('audios/add', 'AudioController@postAdd');
            Route::post('audios/add-audio', 'AudioController@postAddAudio');
            Route::post('audios/edit/{id}', 'AudioController@postEdit');
            Route::post('audios/records', 'AudioController@postRecords');
            Route::post('audios/update-status', 'AudioController@postUpdateStatus');
            Route::post('audios/action', 'AudioController@postAction');
            Route::post('audios/delete-action', 'AudioController@postDeleteAction');
            Route::get('audios/audio-to-edit/{id}', 'AudioController@getAudioToEdit');
            Route::get('audios/complete-audio-details/{id}', 'AudioController@getCompleteAudioDetails');

            /** Artist Routes */
            Route::get('artists/info', 'ArtistController@getInfo');
            Route::post('artists/records', 'ArtistController@postRecords');
            Route::post('artists/update-status', 'ArtistController@postUpdateStatus');
            Route::post('artists/artist-image', 'ArtistController@postArtistImage');
            Route::post('artists/add', 'ArtistController@postAdd');
            ROute::post('artists/edit/{id}', 'ArtistController@postEdit');
            Route::post('artists/action', 'ArtistController@postAction');
            Route::post('artists/delete-artist-image/{id}', 'ArtistController@postDeleteArtistImage');
            Route::get('artists/audio-artists/{id?}', 'ArtistController@getAudioArtists');

            /** Playlists Routes */
            Route::get('playlists/info', 'PlaylistController@getInfo');
            Route::post('playlists/records', 'PlaylistController@postRecords');
            Route::post('playlists/update-status', 'PlaylistController@postUpdateStatus');
            Route::post('playlists/playlist-image/{module}', 'AudioBaseController@postUploadThumbnail');
            Route::post('playlists/add', 'PlaylistController@postAdd');
            ROute::post('playlists/edit/{id}', 'PlaylistController@postEdit');
            Route::post('playlists/action', 'PlaylistController@postAction');
            Route::post('playlists/delete-artist-image/{id}', 'PlaylistController@postDeletePlaylistImage');
            Route::get('playlists/audio-artists/{id?}', 'PlaylistController@getAudioPlaylists');
            Route::get('playlists/searchaudios', 'PlaylistController@getAudioTracks');

            /** Languages Routes */
            Route::get('languages/info', 'LanguageController@getInfo');
            Route::post('languages/records', 'LanguageController@postRecords');
            Route::post('languages/update-status', 'LanguageController@postUpdateStatus');
            Route::post('languages/add', 'LanguageController@postAdd');
            ROute::post('languages/edit/{id}', 'LanguageController@postEdit');
            Route::post('languages/action', 'LanguageController@postAction');

            /** Genres Routes */
            Route::get('genres/info', 'GenreController@getInfo');
            Route::post('genres/records', 'GenreController@postRecords');
            Route::post('genres/update-status', 'GenreController@postUpdateStatus');
            Route::post('genres/add', 'GenreController@postAdd');
            ROute::post('genres/edit/{id}', 'GenreController@postEdit');
            Route::post('genres/action', 'GenreController@postAction');

            /** Ads Routes */
            Route::get('audios/ads/info', 'AdsController@getInfo');        
            Route::post('audios/ads/records', 'AdsController@postRecords');
            Route::post('audios/ads/update-status', 'AdsController@postUpdateStatus');
            Route::post('audios/ads/ad-image', 'AdsController@postAdImage');
            Route::post('audios/ads/add', 'AdsController@postAdd');
            ROute::post('audios/ads/edit/{id}', 'AdsController@postEdit');
            Route::post('audios/ads/action', 'AdsController@postAction');
            Route::post('audios/ads/delete-ad-image/{id}', 'AdsController@postDeleteAdImage');

        });
    });
});

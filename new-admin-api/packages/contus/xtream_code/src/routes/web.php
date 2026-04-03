<?php

use Contus\XtreamCode\Api\Controllers\XtreamController;
use Illuminate\Support\Facades\Route;

/**
 * Xtream Codes API Routes
 * These routes handle the requests from Xtream Codes compatible players.
 */

// Core player API endpoint
// Matches: /player_api.php?username=X&password=X&action=X
Route::any('player_api.php', [XtreamController::class, 'handle']);

// XMLTV EPG
Route::get('xmltv.php', [XtreamController::class, 'xmltv']);
Route::get('xmltv', [XtreamController::class, 'xmltv']);

// Stream handler (get.php) — query-string style
// /get.php?username=X&password=X&stream_id=X
Route::get('get.php', [XtreamController::class, 'stream']);
Route::get('get', [XtreamController::class, 'stream']);

// Standard Xtream URL-rewrite style stream routes
// Players construct these URLs automatically from the stream_id in the API response.
// Live TV:  /live/{username}/{password}/{stream_id}.{ext}
// Movies:   /movie/{username}/{password}/{stream_id}.{ext}
Route::get('live/{username}/{password}/{streamFile}', function ($username, $password, $streamFile) {
    $streamId = pathinfo($streamFile, PATHINFO_FILENAME); // strip .m3u8 / .ts
    return app(XtreamController::class)->stream(request(), $username, $password, $streamId);
});

Route::get('movie/{username}/{password}/{streamFile}', function ($username, $password, $streamFile) {
    $streamId = pathinfo($streamFile, PATHINFO_FILENAME); // strip .mp4 / .mkv
    return app(XtreamController::class)->stream(request(), $username, $password, $streamId);
});



// forthispc0185@gmail.com
// 00000000
// http://15.204.253.153/ott-laravel/new-admin-api/public

// savaliyajay@gmail.com
// 00000000
// https://new-admin-api.test/

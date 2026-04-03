<?php

use Contus\XtreamCode\Api\Controllers\XtreamController;
use Illuminate\Support\Facades\Route;

/**
 * Xtream Codes API Routes
 * These routes handle the requests from Xtream Codes compatible players.
 */
// Route::prefix('api/xtream')->group(function () {
    // The core player API endpoint
    // Matches: /api/xtream/player_api.php
    Route::any('player_api.php', [XtreamController::class, 'handle']);
    
    // XMLTV EPG
    // Matches: /api/xtream/xmltv.php
    // dd(99);
    Route::get('xmltv.php', [XtreamController::class, 'xmltv']);
    Route::get('xmltv', [XtreamController::class, 'xmltv']);

    // Stream handler (get.php)
    // Matches: /api/xtream/get.php
    Route::get('get.php', [XtreamController::class, 'stream']); // For ts/m3u8 redirect
    Route::get('get', [XtreamController::class, 'stream']); // For ts/m3u8 redirect
// });


// forthispc0185@gmail.com
// 00000000
// http://15.204.253.153/ott-laravel/new-admin-api/public

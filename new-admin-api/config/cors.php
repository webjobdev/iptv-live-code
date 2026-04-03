<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'player_api.php', 'live/*', 'movie/*', 'get.php', 'get', 'xmltv.php', 'xmltv'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,

    // 'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // 'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

    // 'allowed_origins' => [
    //     // 'http://localhost:4200',       // Angular dev server
    //     'https://new-admin-api.test' // Production domain
    // ],

    // 'allowed_origins_patterns' => [],

    // 'allowed_headers' => [
    //     'Content-Type',
    //     'X-Requested-With',
    //     'Authorization',
    //     'Accept',
    //     'Origin',
    // ],

    // 'exposed_headers' => [],

    // 'max_age' => 0,

    // 'supports_credentials' => true, 

];

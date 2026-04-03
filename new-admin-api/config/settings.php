<?php

return [

    'default_currency_symbol' => env('CURRENCY_FORMAT'),

    'security-settings' => [
        'authentication' => [
            'lockout_time' => 60,
            'max_login_attempts' => 5,
        ]
    ],

    'general-settings' => [
        'site-settings' => [
            'logo' => 'logo.png',
            'page_title' => 'ISG - Video Platform'
        ]
    ],

];

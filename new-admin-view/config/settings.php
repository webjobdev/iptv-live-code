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
            'favicon' => 'favicon.png',
            'logo' => 'logo_new.png',
            'page_title' => 'IP TV SOlUTION GROUP'
        ]
    ],

];

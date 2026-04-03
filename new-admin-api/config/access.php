<?php
return [

    /*
     |-------------------------------------------
     | Access for each modules and their routes
     |-------------------------------------------
     |
     | The following are the modules and their respective permissions
     |
     */
    'perpage' => 10,

    'modules' => [

        [
            'name' => 'Video Management',
            'access_name' => 'video_management',
            "children" => [
                [
                    'name' => 'Videos',
                    'access_name' => 'videos_all',
                    'description' => 'Video add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'videos_all_write'],
                        ['name' => 'Read', 'access_name' => 'videos_all_read']
                    ],
                ],
                [
                    'name' => 'Live Videos',
                    'access_name' => 'live_videos_all',
                    'description' => 'Live Video add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'live_videos_all_write'],
                        ['name' => 'Read', 'access_name' => 'live_videos_all_read']
                    ],

                ],

                [
                    'name' => 'Live Events',
                    'access_name' => 'liveevents_all',
                    'description' => 'Live Events add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'liveevents_all_write'],
                        ['name' => 'Read', 'access_name' => 'liveevents_all_read']
                    ],

                ],

                [
                    'name' => 'Radio',
                    'access_name' => 'radio_all',
                    'description' => 'Radio add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'radio_all_write'],
                        ['name' => 'Read', 'access_name' => 'radio_all_read']
                    ],

                ],
                [
                    'name' => 'Webseries',
                    'access_name' => 'webseries_all',
                    'description' => 'Webseries add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'webseries_all_write'],
                        ['name' => 'Read', 'access_name' => 'webseries_all_read']
                    ],
                ],
                [
                    'name' => 'Category',
                    'access_name' => 'category_all',
                    'description' => 'Category add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'category_all_write'],
                        ['name' => 'Read', 'access_name' => 'category_all_read']
                    ],

                ],
                [
                    'name' => 'Radio Category',
                    'access_name' => 'radiocategory_all',
                    'description' => 'Radio Category add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'radiocategory_all_write'],
                        ['name' => 'Read', 'access_name' => 'radiocategory_all_read']
                    ],

                ],
                [
                    'name' => 'Live Category',
                    'access_name' => 'livecategory_all',
                    'description' => 'Live Category add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'livecategory_all_write'],
                        ['name' => 'Read', 'access_name' => 'livecategory_all_read']
                    ],

                ],
                [
                    'name' => 'Genre',
                    'access_name' => 'genre_all',
                    'description' => 'Genre add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'genre_all_write'],
                        ['name' => 'Read', 'access_name' => 'genre_all_Read']
                    ],

                ],
                [
                    'name' => 'Seasons',
                    'access_name' => 'season_all',
                    'description' => 'Seasons add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'season_all_write'],
                        ['name' => 'Read', 'access_name' => 'season_all_read']
                    ],

                ],
                [
                    'name' => 'Presets',
                    'access_name' => 'preset_all',
                    'description' => 'Preset add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'preset_all_write'],
                        ['name' => 'Read', 'access_name' => 'preset_all_read']
                    ],

                ],
                [
                    'name' => 'Ads',
                    'access_name' => 'ads_all',
                    'description' => 'Ads add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'ads_all_write'],
                        ['name' => 'Read', 'access_name' => 'ads_all_read']
                    ],

                ],
                [
                    'name' => 'X-ray',
                    'access_name' => 'cast_all',
                    'description' => 'X-ray add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'cast_all_write'],
                        ['name' => 'Read', 'access_name' => 'cast_all_read']
                    ],

                ],
                [
                    'name' => 'Playlist',
                    'access_name' => 'playlists_all',
                    'description' => 'Playlist add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'playlists_all_write'],
                        ['name' => 'Read', 'access_name' => 'playlists_all_read']
                    ],

                ]
            ]
        ],
        [
            'name' => 'User Management',
            'access_name' => 'user_management',
            "children" => [
                [
                    'name' => 'System User',
                    'access_name' => 'system_users',
                    'description' => 'System User add, edit, delete',
                    'children' => [
                        // ['name' => 'write', 'access_name' => 'customer_all_write'],
                        // ['name' => 'Read', 'access_name' => 'customer_all_read']
                    ],

                ],
                [
                    'name' => 'Customer',
                    'access_name' => 'customer_all',
                    'description' => 'Customer add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'customer_all_write'],
                        ['name' => 'Read', 'access_name' => 'customer_all_read']
                    ],

                ],
                [
                    'name' => 'Admin User',
                    'access_name' => 'admin_user_all',
                    'description' => 'User add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'admin_user_all_write'],
                        ['name' => 'Read', 'access_name' => 'admin_user_all_read']
                    ],

                ],
                [
                    'name' => 'User Group',
                    'access_name' => 'usergroup_all',
                    'description' => 'User Group add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'usergroup_all_write'],
                        ['name' => 'Read', 'access_name' => 'usergroup_all_read']
                    ],

                ],
                [
                    'name' => 'Permission Rules',
                    'access_name' => 'permission_rules',
                    'description' => 'Permission Rules Add, Edit & Delete',
                    'children' => []
                ],
            ]
        ],
        [
            'name' => 'Subscriptions',
            'access_name' => 'subscription_all',
            'children' => [
                ['name' => 'write', 'access_name' => 'subscription_all_write'],
                ['name' => 'Read', 'access_name' => 'subscription_all_read']
            ],
        ],
        [
            'name' => 'Transactions',
            'access_name' => 'transaction_all',
            "children" => []

        ],
        [
            'name' => 'Coupons',
            'access_name' => 'coupons_all',
            'children' => [
                ['name' => 'write', 'access_name' => 'coupons_all_write'],
                ['name' => 'Read', 'access_name' => 'coupons_all_read']
            ],

        ],
        [
            'name' => 'Site Configuration',
            'access_name' => 'content_management',
            "children" => [
                [
                    'name' => 'Site Settings',
                    'access_name' => 'settings_all',
                    'description' => 'setting add, edit, delete',
                    'children' => [],

                ],
                [
                    'name' => 'Static Pages',
                    'access_name' => 'static_page_all',
                    'description' => 'Static Pages add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'static_page_all_write'],
                        ['name' => 'Read', 'access_name' => 'static_page_all_Read']
                    ],

                ],
                [
                    'name' => 'Email',
                    'access_name' => 'email_all',
                    'description' => 'Email add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'email_all_write'],
                        ['name' => 'Read', 'access_name' => 'email_all_read']
                    ],

                ],
                [
                    'name' => 'Banner',
                    'access_name' => 'banner_all',
                    'description' => 'Banner add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'banner_all_write'],
                        ['name' => 'Read', 'access_name' => 'banner_all_read']
                    ],

                ],
                [
                    'name' => 'Kids Banner',
                    'access_name' => 'kidsbanner_all',
                    'description' => 'Kids Banner add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'kidsbanner_all_write'],
                        ['name' => 'Read', 'access_name' => 'kidsbanner_all_read']
                    ],

                ],
                [
                    'name' => 'Home and Footer Banner',
                    'access_name' => 'homefooterbanner_all',
                    'description' => 'Home and Footer Banner add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'homefooterbanner_all_write'],
                        ['name' => 'Read', 'access_name' => 'homefooterbanner_all_read']
                    ],

                ],
                [
                    'name' => 'Landingpage Banner',
                    'access_name' => 'landingbanner_all',
                    'description' => 'Landingpage Banner add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'landingbanner_all_write'],
                        ['name' => 'Read', 'access_name' => 'landingbanner_all_read']
                    ],

                ],
                [
                    'name' => 'Live Banner',
                    'access_name' => 'livebanner_all',
                    'description' => 'Live Banner add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'livebanner_all_write'],
                        ['name' => 'Read', 'access_name' => 'livebanner_all_read']
                    ],

                ]
            ]
        ],
        [
            'name' => 'Analytics',
            'access_name' => 'analytics_management',
            "children" => [
                [
                    'name' => 'Google Analytics',
                    'access_name' => 'google_analytics_all',
                    'description' => 'View Google analytics',
                    'children' => []
                ],
                [
                    'name' => 'Region Wise View',
                    'access_name' => 'region_wise_view_all',
                    'description' => 'View region wise statistics',
                    'children' => []
                ],
                [
                    'name' => 'Top Category',
                    'access_name' => 'top_category_all',
                    'description' => 'View top category wise statistics',
                    'children' => []
                ],
                [
                    'name' => 'Most Viewed Video',
                    'access_name' => 'most_viewed_all',
                    'description' => 'View most viewed video statistics',
                    'children' => []
                ],
                [
                    'name' => 'Most Favourite Video',
                    'access_name' => 'most_favourite_all',
                    'description' => 'View most favourited video statistics',
                    'children' => []
                ]
                // [
                //     'name' => 'Most Commented Video',
                //     'access_name' => 'most_commented_all',
                //     'description' => 'View most commented video statistics',
                //     'children' => []
                // ]
            ]
        ],
        [
            'name' => 'Geo Fencing',
            'access_name' => 'geofencing_management',
            'description' => 'View Geo fencing',
            "children" => []
        ],
        [
            'name' => 'Organizations',
            'access_name' => 'organizations',
            'description' => 'View Organizations',
            "children" => []
        ],
        [
            'name' => 'Drm Service',
            'access_name' => 'drm_service',
            'description' => 'View Drm Service',
            "children" => []
        ],
        [
            'name' => 'Subscribers',
            'access_name' => 'subscribers',
            'description' => 'View Subscribers',
            "children" => []
        ],
        [
            'name' => 'Settings',
            'access_name' => 'settings',
            'description' => 'Settings',
            "children" => [
                [
                    'name' => 'General Settings',
                    'access_name' => 'general_settings',
                    'description' => 'General Settings'
                ],
                [
                    'name' => 'Subscriber Setting Service',
                    'access_name' => 'subscriber_setting_service',
                    'description' => 'Subscriber Setting Service'
                ],
                [
                    'name' => 'Payment Service Setting',
                    'access_name' => 'payment_service_setting',
                    'description' => 'Payment Service Setting'
                ],
                [
                    'name' => 'Extensions',
                    'access_name' => 'extensions',
                    'description' => 'Extensions'
                ],
                [
                    'name' => 'Dashboard Configuration',
                    'access_name' => 'dashboard_configuration',
                    'description' => 'Dashboard Configuration'
                ],
            ]
        ],
        [
            'name' => 'Api Access',
            'access_name' => 'api_access',
            'description' => 'api_access',
            "children" => []
        ],
        [
            'name' => 'Channels',
            'access_name' => 'channels',
            'description' => 'Channels',
            'children' => []
        ],
        [
            'name' => 'Video On Demand',
            'access_name' => 'vod',
            'description' => 'Video On Demand',
            'children' => []
        ],
        [
            'name' => 'Tv Show',
            'access_name' => 'tv_show',
            'description' => 'Tv Show',
            'children' => []
        ],
        [
            'name' => 'Stream Services',
            'access_name' => 'stream_services',
            'description' => 'Stream Services',
            "children" => []
        ],
        [
            'name' => 'Tv Category',
            'access_name' => 'tv_category_all',
            'description' => 'Category add, edit, delete',
            'children' => [
                ['name' => 'write', 'access_name' => 'tv_category_all_write'],
                ['name' => 'Read', 'access_name' => 'tv_category_all_read']
            ],
        ],
        [
            'name' => 'Vod Category',
            'access_name' => 'vod_category_all',
            'description' => 'Category add, edit, delete',
            'children' => [
                ['name' => 'write', 'access_name' => 'vod_category_all_write'],
                ['name' => 'Read', 'access_name' => 'vod_category_all_read']
            ],
        ],
        [
            'name' => 'Series Category',
            'access_name' => 'series_category_all',
            'description' => 'Category add, edit, delete',
            'children' => [
                ['name' => 'write', 'access_name' => 'series_category_all_write'],
                ['name' => 'Read', 'access_name' => 'series_category_all_read']
            ],
        ],

        [
            'name' => 'Channels Services',
            'access_name' => 'channels_services',
            "children" => [
                [
                    'name' => 'Catch-up TV',
                    'access_name' => 'catchup_tv',
                    'description' => 'Category add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'catchup_tv_write'],
                        ['name' => 'Read', 'access_name' => 'catchup_tv_read']
                    ],
                ],
                [
                    'name' => 'Live Rewind',
                    'access_name' => 'live_rewind',
                    'description' => 'Category add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'live_rewind_write'],
                        ['name' => 'Read', 'access_name' => 'live_rewind_read']
                    ],
                ],
                [
                    'name' => 'EPG Service',
                    'access_name' => 'epg_service',
                    'description' => 'Category add, edit, delete',
                    'children' => [
                        ['name' => 'write', 'access_name' => 'epg_service_write'],
                        ['name' => 'Read', 'access_name' => 'epg_service_read']
                    ],
                ],
            ]
        ],
        [
            'name' => 'Partner Programs',
            'access_name' => 'partner_programs',
            'description' => 'Partner Programs',
            'children' => []
        ],
        [
            'name' => 'Reports',
            'access_name' => 'reports',
            'description' => 'Reports',
            'children' => [
                [
                    'name' => 'Subscriber Reports',
                    'access_name' => 'subscriber_reports',
                    'description' => 'Subscriber Reports'
                ],
                [
                    'name' => 'Cps Reports',
                    'access_name' => 'cps_reports',
                    'description' => 'Cps Reports'
                ],
                [
                    'name' => 'Activation Reports',
                    'access_name' => 'activation_reports',
                    'description' => 'Activation Reports'
                ]
            ]
        ],
        [
            'name' => 'Devices',
            'access_name' => 'devices',
            'description' => 'Devices',
            'children' => []
        ],
        [
            'name' => 'Geo Blocking',
            'access_name' => 'geo_blocking',
            'description' => 'Geo Blocking',
            'children' => []
        ],
        // [
        //     'name' => 'Dashboard Configuration',
        //     'access_name' => 'dashboard_configuration',
        //     'description' => 'Dashboard Configuration',
        //     'children' => []
        // ]
    ],
];

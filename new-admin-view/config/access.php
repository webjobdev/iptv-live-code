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
    'role_modules' => [
        'video_management' => [
            'name' => 'Video Management',
            'sub_modules' => [
                ['name' => 'Video'],
                ['name' => 'Live Videos'],
                ['name' => 'Radio'],
                ['name' => 'Radio Category'],
                ['name' => 'Category'],
                ['name' => 'Genre'],
                ['name' => 'Season'],
                ['name' => 'Preset'],
                ['name' => 'Ads']
            ]
        ],
        'audio_management' => [
            'name' => 'Audio Management',
            'sub_modules' => [
                ['name' => 'Albums'],
                ['name' => 'Audios'],
                ['name' => 'Artists'],
                ['name' => 'Languages']
            ]
        ],
        'user_management' => [
            'name' => 'User Management',
            'sub_modules' => [
                ['name' => 'Customer'],
                ['name' => 'User'],
                ['name' => 'User Group']
            ]
        ],
        'subscription' => [
            'name' => 'Subscriptions',
            'sub_modules' => [],
            'permission' => [

                'Write' => 'write',
                'Read' => 'read',
            ]
        ],
        'transaction' => [
            'name' => 'Transactions',
            'sub_modules' => []
        ],
        'Contents' => [
            'name' => 'Site Configuration',
            'sub_modules' => [
                ['name' => 'Site Settings'],
                ['name' => 'Email'],
                ['name' => 'Static Contents'],
                ['name' => 'Banner'],
            ]
        ],
        'analytics' => [
            'name' => 'Analytics',
            'sub_modules' => [
                ['name' => 'Video Statistics'],
                ['name' => 'Region Wise View'],
                ['name' => 'Top Category'],
                ['name' => 'Most Favourite Video'],
                ['name' => 'Most Commented Video']
            ]
        ],
        'dashboard' => [
            'name' => 'Dashboard',
            'sub_modules' => []
        ],
        'manage account' => [
            'name' => 'Manage Account',
            'sub_modules' => [
                ['name' => 'My Profile'],
                ['name' => 'Change Password'],
            ]
        ]
    ],


    'modules' => [
        [
            'name' => 'Dashboard',
            'access' => 'dashboard_all',
            "sub_module" => []

        ],
        [
            'name' => 'Video Management',
            'access' => 'video_management',
            "sub_module" => [
                [
                    'name' => 'Videos',
                    'access' => 'videos_all',
                    'description' => 'Video add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Live Videos',
                    'access' => 'live_videos_all',
                    'description' => 'Live Video add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Live Events',
                    'access' => 'liveevents_all',
                    'description' => 'Live Events add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Radio',
                    'access' => 'radio_all',
                    'description' => 'Radio add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Webseries',
                    'access' => 'webseries_all',
                    'description' => 'Webseries add, edit, delete',
                    'permission' => [
                        'Write' => 'write',
                        'Read' => 'read',
                    ],
                ],
                [
                    'name' => 'Category',
                    'access' => 'category_all',
                    'description' => 'Category add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Radio Category',
                    'access' => 'radiocategory_all',
                    'description' => 'Radio Category add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Live Category',
                    'access' => 'livecategory_all',
                    'description' => 'Live Category add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Genre',
                    'access' => 'genre_all',
                    'description' => 'Genre add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Seasons',
                    'access' => 'season_all',
                    'description' => 'Seasons add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Presets',
                    'access' => 'preset_all',
                    'description' => 'Preset add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Ads',
                    'access' => 'ads_all',
                    'description' => 'Ads add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],

            ]
        ],
        // [
        //     'name' => 'Audio Management',
        //     'access' => 'audio_management',
        //     "sub_module" => [
        //         [
        //             'name' => 'Albums',
        //             'access' => 'albums_all',
        //             'description' => 'Albums add, edit, delete',
        //             'permission' => [

        //                 'Write' => 'write',
        //                 'Read' => 'read',

        //             ],
        //         ],
        //         [
        //             'name' => 'Audios',
        //             'access' => 'audios_all',
        //             'description' => 'Audios add, edit, delete',
        //             'permission' => [

        //                 'Write' => 'write',
        //                 'Read' => 'read',

        //             ],
        //         ],
        //         [
        //             'name' => 'Artists',
        //             'access' => 'artists_all',
        //             'description' => 'Artists add, edit, delete',
        //             'permission' => [

        //                 'Write' => 'write',
        //                 'Read' => 'read',

        //             ],
        //         ],
        //         [
        //             'name' => 'Languages',
        //             'access' => 'languages_all',
        //             'description' => 'Languages add, edit, delete',
        //             'permission' => [

        //                 'Write' => 'write',
        //                 'Read' => 'read',

        //             ],
        //         ]
        //     ]
        // ],
        [
            'name' => 'User Management',
            'access' => 'user_management',
            "sub_module" => [
                [
                    'name' => 'Customer',
                    'access' => 'customer_all',
                    'description' => 'Customer add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'User',
                    'access' => 'user_all',
                    'description' => 'User add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'User Group',
                    'access' => 'usergroup_all',
                    'description' => 'User Group add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ]
            ]
        ],
        [
            'name' => 'Subscriptions',
            'access' => 'subscription_all',
            "sub_module" => [],
            "permission" => [

                'Write' => 'write',
                'Read' => 'read',

            ],

        ],
        [
            'name' => 'Transactions',
            'access' => 'transaction_all',
            "sub_module" => []

        ],
        [
            'name' => 'Site Configuration',
            'access' => 'content_management',
            "sub_module" => [
                [
                    'name' => 'Site Settings',
                    'access' => 'settings_all',
                    'description' => 'setting add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Email',
                    'access' => 'email_all',
                    'description' => 'Email add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Static Pages',
                    'access' => 'static_page_all',
                    'description' => 'Static Pages add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Banner',
                    'access' => 'banner_all',
                    'description' => 'Banner add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Kids Banner',
                    'access' => 'kidsbanner_all',
                    'description' => 'Kids Banner add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Landingpage Banner',
                    'access' => 'landingbanner_all',
                    'description' => 'Landingpage Banner add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Home and Kids footer Banner',
                    'access' => 'homefooterbanner_all',
                    'description' => 'Home and Kids footer Banner add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Live Banner',
                    'access' => 'livebanner_all',
                    'description' => 'Live Banner add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ]
            ]
        ],
        [
            'name' => 'Analytics',
            'access' => 'analytics_management',
            "sub_module" => [
                [
                    'name' => 'Video Statistics',
                    'access' => 'video_statistics',
                    'description' => 'Video Statistics add, edit, delete',
                    'permission' => [],
                ],
                [
                    'name' => 'Region Wise View',
                    'access' => 'region_wise_view_all',
                    'description' => 'Region add, edit, delete',
                    'permission' => [],
                ],
                [
                    'name' => 'Top Category',
                    'access' => 'top_category_all',
                    'description' => 'Top Category add, edit, delete',
                    'permission' => [],
                ],
                [
                    'name' => 'Most Favourite Video',
                    'access' => 'most_favourite_all',
                    'description' => 'Most Favourite add, edit, delete',
                    'permission' => [],
                ],
                [
                    'name' => 'Most Viewed Video',
                    'access' => 'most_viewed_all',
                    'description' => 'Most Viewed Video add, edit, delete',
                    'permission' => [],
                ],
                [
                    'name' => 'Most Commented Video',
                    'access' => 'most_commented_all',
                    'description' => 'Most Commented Video add, edit, delete',
                    'permission' => [],
                ]
            ]
        ],
        [
            'name' => 'Manage Accounts',
            'access' => 'account_management',
            "sub_module" => [

                [
                    'name' => 'My Profile',
                    'access' => 'profile_page_all',
                    'description' => 'Pages add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ],
                [
                    'name' => 'Change Password',
                    'access' => 'change_password_all',
                    'description' => 'password add, edit, delete',
                    'permission' => [

                        'Write' => 'write',
                        'Read' => 'read',

                    ],
                ]
            ]
        ],
    ],
    'permissionRoutes' => [

        'generalAccess' => [
            'AdminUserController@getProfile'
        ],
        'dashboard_all' => [
            'DashboardController@getIndex',
            'DashboardController@getInfo',
            'DashboardController@getVideoStatistics',
            'DashboardController@getSignedCustomer',
            'DashboardController@getSubscribedUserData',
            'DashboardController@getRevenue',
            'DashboardController@getRevenueData',
            'DashboardController@regionWiseVideoCountAnalytics',
            'DashboardController@platformWiseVideoCountAnalytics',
            'DashboardController@getSubscribedUserCount'

        ],
        'video_management' => [
            'VideoController@getIndex',
            'VideoController@getViewDetailsVideo',
            'VideoController@getGrid',
            'VideoController@getVideoUpload',
            'CategoryController@getIndex',
            'CategoryController@getViewDetailsVideo',
            'CategoryController@getGrid',
            'RadioCategoryController@getIndex',
            'RadioCategoryController@getViewDetailsVideo',
            'RadioCategoryController@getGrid',
            'GroupsController@getIndex',
            'GroupsController@getViewDetailsVideo',
            'GroupsController@getGrid',
            'SeasonController@getIndex',
            'SeasonController@getViewDetailsVideo',
            'SeasonController@getGrid',
            'PresetController@getIndex',
            'PresetController@getViewDetailsVideo',
            'PresetController@getGrid',

        ],
        'videos_all' => [
            'VideoController@getIndex',
            'VideoController@getViewDetailsVideo',
            'VideoController@getGrid',
            'VideoController@getVideoUpload',
        ],
        'videos_all_write' => [
            'VideoController@getGridlist',
            'VideoController@getDetailsVideoEdit',
            'VideoController@getViewDetailsVideo',
            'VideoController@getGrid',
            'VideoController@getAdd',
            'VideoController@getVideoUpload',

        ],
        'videos_all_read' => [
            'VideoController@getIndex',
            'VideoController@getViewDetailsVideo',
            'VideoController@getGrid',
            'VideoController@getGridlist',
        ],
        'webseries_all' => [
            'WebseriesController@getIndex',
            'WebseriesController@getViewDetailsVideo',
            'WebseriesController@getGrid',
            'WebseriesController@getGridlist',
        ],
        'webseries_all_write' => [
            'WebseriesController@getIndex',
            'WebseriesController@getViewDetailsVideo',
            'WebseriesController@getGrid',
            'WebseriesController@getAdd',
        ],
        'webseries_all_read' => [
            'WebseriesController@getIndex',
            'WebseriesController@getViewDetailsVideo',
            'WebseriesController@getGrid',
        ],
        'category_all' => [
            'CategoryController@getIndex',
            'CategoryController@getViewDetailsVideo',
            'CategoryController@getGrid',
            'CategoryController@getGridlist',
        ],
        'category_all_write' => [
            'CategoryController@getIndex',
            'CategoryController@getViewDetailsVideo',
            'CategoryController@getGrid',
        ],
        'category_all_read' => [
            'CategoryController@getIndex',
            'CategoryController@getViewDetailsVideo',
            'CategoryController@getGrid',
        ],
        'radiocategory_all' => [
            'RadioCategoryController@getIndex',
            'RadioCategoryController@getViewDetailsVideo',
            'RadioCategoryController@getGrid',
            'RadioCategoryController@getGridlist',
        ],
        'radiocategory_all_write' => [
            'RadioCategoryController@getIndex',
            'RadioCategoryController@getViewDetailsVideo',
            'RadioCategoryController@getGrid',
        ],
        'radiocategory_all_read' => [
            'RadioCategoryController@getIndex',
            'RadioCategoryController@getViewDetailsVideo',
            'RadioCategoryController@getGrid',
        ],
        'livecategory_all' => [
            'LiveCategoryController@getIndex',
            'LiveCategoryController@getViewDetailsVideo',
            'LiveCategoryController@getGrid',
            'LiveCategoryController@getGridlist',
        ],
        'livecategory_all_write' => [
            'LiveCategoryController@getIndex',
            'LiveCategoryController@getViewDetailsVideo',
            'LiveCategoryController@getGrid',
        ],
        'livecategory_all_read' => [
            'LiveCategoryController@getIndex',
            'LiveCategoryController@getViewDetailsVideo',
            'LiveCategoryController@getGrid',
        ],
        'genre_all' => [
            'GenreController@getIndex',
            'GenreController@getViewDetailsVideo',
            'GenreController@getGrid',
            'GenreController@getGridlist',
        ],
        'genre_all_write' => [
            'GenreController@getIndex',
            'GenreController@getViewDetailsVideo',
            'GenreController@getGrid',
        ],
        'genre_all_read' => [
            'GenreController@getIndex',
            'GenreController@getViewDetailsVideo',
            'GenreController@getGrid',
        ],
        'season_all' => [
            'SeasonController@getIndex',
            'SeasonController@getViewDetailsVideo',
            'SeasonController@getGrid',
            'SeasonController@getGridlist',
        ],
        'season_all_write' => [
            'SeasonController@getIndex',
            'SeasonController@getViewDetailsVideo',
            'SeasonController@getGrid',
            'SeasonController@getGridlist',
        ],
        'season_all_read' => [
            'SeasonController@getIndex',
            'SeasonController@getViewDetailsVideo',
            'SeasonController@getGrid',
        ],
        'preset_all' => [
            'PresetController@getIndex',
            'PresetController@getViewDetailsVideo',
            'PresetController@getGrid',
            'PresetController@getGridlist',
        ],
        'preset_all_write' => [
            'PresetController@getIndex',
            'PresetController@getViewDetailsVideo',
            'PresetController@getGrid',
        ],
        'preset_all_read' => [
            'PresetController@getIndex',
            'PresetController@getViewDetailsVideo',
            'PresetController@getGrid',
        ],
        'user_management' => [
            'AdminUserController@getIndex',
            'AdminUserController@getEdit',
            'AdminUserController@postUpdate',
            'AdminUserController@getUnique',
            'AdminUserController@getGrid',
            'AdminUserController@getGridlist',
            'AdminUserController@postRecords',
            'CustomerUserController@getIndex',
            'CustomerUserController@getGridlist',
            'AdminUserGroupController@getIndex',
            'AdminUserGroupController@getGridlist',
            'AdminUserGroupController@getAdd',
            'AdminUserGroupController@getEdit',
        ],
        'customer_all' => [
            'CustomerUserController@getIndex',
            'CustomerUserController@getGridlist',
            'CustomerAuthController@getInfo',
            'CustomerAuthController@postRecords',
        ],
        'customer_all_write' => [
            'CustomerUserController@getIndex',
            'CustomerUserController@getGridlist',
        ],
        'customer_all_read' => [
            'CustomerUserController@getIndex',
            'CustomerUserController@getGridlist',

        ],
        'user_all' => [
            'AdminUserController@getIndex',
            'AdminUserController@getInfo',
            'AdminUserController@getChangePasswordInfo',
            'AdminUserController@postChangepassword',
            'AdminUserController@postAdd',
            'AdminUserController@postEdit',
            'AdminUserController@postDeleteProfileImage',
            'AdminUserController@getEdit',
            'AdminUserController@getUpdategridview',
            'AdminUserController@getUnique',
            'AdminUserController@postProfileImage',
            'AdminUserController@getAdd',
            'AdminUserController@getEdit',
            'AdminUserController@postUpdate',
            'AdminUserController@getDestroy',
            'AdminUserController@postAction',
            'AdminUserController@getChangepassword',
            'AdminUserController@getProfile',
            'AdminUserController@postProfile',
            'AdminUserController@getUnique',
            'AdminUserController@getGrid',
            'AdminUserController@getGridlist',
            'AdminUserController@getLogout',
        ],
        'user_all_write' => [
            'AdminUserController@getIndex',
            'AdminUserController@getInfo',
            'AdminUserController@getChangePasswordInfo',
            'AdminUserController@postChangepassword',
            'AdminUserController@postAdd',
            'AdminUserController@postEdit',
            'AdminUserController@postDeleteProfileImage',
            'AdminUserController@getEdit',
            'AdminUserController@getUpdategridview',
            'AdminUserController@getUnique',
            'AdminUserController@postProfileImage',
            'AdminUserController@getAdd',
            'AdminUserController@getEdit',
            'AdminUserController@postUpdate',
            'AdminUserController@getDestroy',
            'AdminUserController@postAction',
            'AdminUserController@getChangepassword',
            'AdminUserController@getProfile',
            'AdminUserController@postProfile',
            'AdminUserController@getUnique',
            'AdminUserController@getGrid',
            'AdminUserController@getGridlist',
            'AdminUserController@getLogout',
        ],
        'user_all_read' => [
            'AdminUserController@getIndex',
            'AdminUserController@getEdit',
            'AdminUserController@postUpdate',
            'AdminUserController@getUnique',
            'AdminUserController@getGrid',
            'AdminUserController@getGridlist',
            'AdminUserController@postRecords',
        ],
        'usergroup_all' => [
            'AdminUserGroupController@getIndex',
            'AdminUserGroupController@getGridlist',
            'AdminUserGroupController@getAdd',
            'AdminUserGroupController@getEdit',
        ],
        'usergroup_all_write' => [
            'AdminUserGroupController@getIndex',
            'AdminUserGroupController@getGridlist',
            'AdminUserGroupController@getAdd',
            'AdminUserGroupController@postAdd',
            'AdminUserGroupController@getEdit',
            'AdminUserGroupController@postUpdate',
        ],
        'usergroup_all_read' => [
            'AdminUserGroupController@getIndex',
            'AdminUserGroupController@getGridlist',
            'AdminUserGroupController@getAdd',
            'AdminUserGroupController@getEdit',
        ],
        'subscription_all' => [
            'SubscriptionPlanController@getIndex',
            'SubscriptionPlanController@getGridlist',
        ],
        'subscription_all_write' => [
            'SubscriptionPlanController@getIndex',
            'SubscriptionPlanController@getGridlist',
        ],
        'subscription_all_read' => [
            'SubscriptionPlanController@getIndex',
            'SubscriptionPlanController@getGridlist',
        ],
        'transaction_all' => [
            'TransactionController@getIndex',
            'TransactionController@getGridlist',
            'TransactionController@getTransactionDetails',
            'CustomerTransactionController@getIndex',
            'CustomerTransactionController@getGridlist',
            'CustomerTransactionController@getTransactionDetails',
            'PaymentController@getIndex',
            'PaymentController@getGridlist',
        ],
        'content_management' => [
            'EmailController@getIndex',
            'EmailController@getGridlist',
            'EmailController@getDetailsEmailEdit',
            'EmailController@getIndex',
            'EmailController@getGridlist',
            'BannerController@getIndex',
            'BannerController@getGridlist',
            'BannerController@postBannerImage',
            'BannerController@postEdit',
            'KidsBannerController@getIndex',
            'KidsBannerController@getGridlist',
            'KidsBannerController@postBannerImage',
            'KidsBannerController@postEdit',
            'LandingBannerController@getIndex',
            'LandingBannerController@getGridlist',
            'LandingBannerController@postBannerImage',
            'LandingBannerController@postEdit',
            'HomeFooterBannerController@getIndex',
            'HomeFooterBannerController@getGridlist',
            'HomeFooterBannerController@postBannerImage',
            'HomeFooterBannerController@postEdit',
            'LiveBannerController@getIndex',
            'LiveBannerController@getGridlist',
            'LiveBannerController@postBannerImage',
            'LiveBannerController@postEdit',
            'StaticContentController@getIndex',
            'StaticContentController@getGridlist',
            'StaticContentController@addStaticContent',
            'StaticContentController@getEditStaticContent',
        ],
        'email_all' => [
            'EmailController@getIndex',
            'EmailController@getGridlist',
            'EmailController@getDetailsEmailEdit',
            'EmailController@getIndex',
            'EmailController@getGridlist',
        ],
        'email_all_write' => [
            'EmailController@getIndex',
            'EmailController@getGridlist',
            'EmailController@getDetailsEmailEdit',
            'EmailController@getIndex',
            'EmailController@getGridlist',
        ],
        'email_all_read' => [
            'EmailController@getIndex',
            'EmailController@getGridlist',
            'EmailController@getIndex',
            'EmailController@getGridlist',
        ],
        'static_page_all' => [
            'StaticContentController@getIndex',
            'StaticContentController@getGridlist',
            'StaticContentController@addStaticContent',
            'StaticContentController@getEditStaticContent',
        ],
        'static_page_all_write' => [
            'StaticContentController@getIndex',
            'StaticContentController@getGridlist',
            'StaticContentController@addStaticContent',
            'StaticContentController@getEditStaticContent',
        ],
        'static_page_all_read' => [
            'StaticContentController@getIndex',
            'StaticContentController@getGridlist',
            'StaticContentController@addStaticContent',
            'StaticContentController@getEditStaticContent',
        ],
        'banner_all' => [
            'BannerController@getIndex',
            'BannerController@getGridlist',
            'BannerController@postBannerImage',
            'BannerController@postEdit',
        ],
        'banner_all_write' => [
            'BannerController@getIndex',
            'BannerController@getGridlist',
            'BannerController@postBannerImage',
            'BannerController@postEdit',
        ],
        'banner_all_read' => [
            'BannerController@getIndex',
            'BannerController@getGridlist',
            'BannerController@postBannerImage',
            'BannerController@postEdit',
        ],
        'kidsbanner_all' => [
            'KidsBannerController@getIndex',
            'KidsBannerController@getGridlist',
            'KidsBannerController@postBannerImage',
            'KidsBannerController@postEdit',
        ],
        'kidsbanner_all_write' => [
            'KidsBannerController@getIndex',
            'KidsBannerController@getGridlist',
            'KidsBannerController@postBannerImage',
            'KidsBannerController@postEdit',
        ],
        'kidsbanner_all_read' => [
            'KidsBannerController@getIndex',
            'KidsBannerController@getGridlist',
            'KidsBannerController@postBannerImage',
            'KidsBannerController@postEdit',
        ],
        'landingbanner_all' => [
            'LandingBannerController@getIndex',
            'LandingBannerController@getGridlist',
            'LandingBannerController@postBannerImage',
            'LandingBannerController@postEdit',
        ],
        'landingbanner_all_write' => [
            'LandingBannerController@getIndex',
            'LandingBannerController@getGridlist',
            'LandingBannerController@postBannerImage',
            'LandingBannerController@postEdit',
        ],
        'landingbanner_all_read' => [
            'LandingBannerController@getIndex',
            'LandingBannerController@getGridlist',
            'LandingBannerController@postBannerImage',
            'LandingBannerController@postEdit',
        ],
        'homefooterbanner_all' => [
            'HomeFooterBannerController@getIndex',
            'HomeFooterBannerController@getGridlist',
            'HomeFooterBannerController@postBannerImage',
            'HomeFooterBannerController@postEdit',
        ],
        'homefooterbanner_all_write' => [
            'HomeFooterBannerController@getIndex',
            'HomeFooterBannerController@getGridlist',
            'HomeFooterBannerController@postBannerImage',
            'HomeFooterBannerController@postEdit',
        ],
        'homefooterbanner_all_read' => [
            'HomeFooterBannerController@getIndex',
            'HomeFooterBannerController@getGridlist',
            'HomeFooterBannerController@postBannerImage',
            'HomeFooterBannerController@postEdit',
        ],
        'livebanner_all' => [
            'LiveBannerController@getIndex',
            'LiveBannerController@getGridlist',
            'LiveBannerController@postBannerImage',
            'LiveBannerController@postEdit',
        ],
        'livebanner_all_write' => [
            'LiveBannerController@getIndex',
            'LiveBannerController@getGridlist',
            'LiveBannerController@postBannerImage',
            'LiveBannerController@postEdit',
        ],
        'livebanner_all_read' => [
            'LiveBannerController@getIndex',
            'LiveBannerController@getGridlist',
            'LiveBannerController@postBannerImage',
            'LiveBannerController@postEdit',
        ],
        'analytics_management' => [
            'ReportsController@getAnalyticsvideo',
            'ReportsController@getIndexRoute',
            'ReportsController@gridlist',
        ],
        'video_statistics' => [
            'ReportsController@getAnalyticsvideo',
            'ReportsController@getIndexRoute',
            'ReportsController@gridlist',
        ],
        'region_wise_view_all' => [
            'ReportsController@getAnalyticsvideo',
            'ReportsController@getIndexRoute',
            'ReportsController@gridlist',
        ],
        'top_category_all' => [
            'ReportsController@getAnalyticsvideo',
            'ReportsController@getIndexRoute',
            'ReportsController@gridlist',
        ],
        'most_favourite_all' => [
            'ReportsController@getAnalyticsvideo',
            'ReportsController@getIndexRoute',
            'ReportsController@gridlist',
        ],
        'most_viewed_all' => [
            'ReportsController@getAnalyticsvideo',
            'ReportsController@getIndexRoute',
            'ReportsController@gridlist',
        ],
        'most_commented_all' => [
            'ReportsController@getAnalyticsvideo',
            'ReportsController@getIndexRoute',
            'ReportsController@gridlist',
        ],
        'settings_all' => [
            'SettingsController@getIndex',
            'SettingsController@postUpdate',
        ],
        'audio_management' => [
            'AlbumController@getAlbumAudios',
            'AlbumController@getInfo',
            'AlbumController@postAdd',
            'AlbumController@postEdit',
            'AlbumController@postAudioBulkUpdate',
            'AlbumController@getAudioAlbums',
            'ArtistController@getAritstAudios',
            'ArtistController@getArtistList',
            'ArtistController@getAdd',
            'ArtistController@postAdd',
            'ArtistController@postEdit',
            'ArtistController@postArtistImage',
            'ArtistController@postDeleteArtistImage',
            'ArtistController@getInfo',
            'ArtistController@getAudioArtists',
            'AudioBaseController@getIndex',
            'AudioBaseController@getGridlist',
            'AudioBaseController@getAdd',
            'AudioBaseController@getEdit',
            'AudioBaseController@getDetailsAudioEdit',
            'AudioBaseController@getAlbumAudios',
            'AudioBaseController@getViewDetailsAudio',
            'AudioBaseController@postUploadThumbnail',
            'AudioController@getDetailsAudioEdit',
            'AudioController@getViewDetailsAudio',
            'AudioController@postHandleFineUploader',
            'AudioController@postAdd',
            'AudioController@postEdit',
            'AudioController@getInfo',
            'AudioController@postDeleteAction',
            'AudioController@postBulkUpdateStatus',
            'AudioController@getAudioToEdit',
            'AudioController@getCompleteAudioDetails',
            'DashboardController@getIndex',
            'LanguageController@getAdd',
            'LanguageController@postAdd',
            'LanguageController@postEdit',
            'LanguageController@getInfo',
        ],
        'albums_all' => [
            'AlbumController@getAlbumAudios',
            'AlbumController@getInfo',
            'AlbumController@postAdd',
            'AlbumController@postEdit',
            'AlbumController@postAudioBulkUpdate',
            'AlbumController@getAudioAlbums',
        ],
        'albums_all_write' => [
            'AlbumController@getAlbumAudios',
            'AlbumController@getInfo',
            'AlbumController@postAdd',
            'AlbumController@postEdit',
            'AlbumController@postAudioBulkUpdate',
            'AlbumController@getAudioAlbums',
        ],
        'albums_all_read' => [
            'AlbumController@getAlbumAudios',
            'AlbumController@getInfo',
            'AlbumController@postAdd',
            'AlbumController@postEdit',
            'AlbumController@postAudioBulkUpdate',
            'AlbumController@getAudioAlbums',
        ],
        'audios_all' => [
            'AudioController@postHandleFineUploader',
            'AudioController@postAdd',
            'AudioController@postEdit',
            'AudioController@getInfo',
            'AudioController@postDeleteAction',
            'AudioController@postBulkUpdateStatus',
            'AudioController@getAudioToEdit',
            'AudioController@getCompleteAudioDetails',
        ],
        'audios_all_write' => [
            'AudioController@postHandleFineUploader',
            'AudioController@postAdd',
            'AudioController@postEdit',
            'AudioController@getInfo',
            'AudioController@postDeleteAction',
            'AudioController@postBulkUpdateStatus',
            'AudioController@getAudioToEdit',
            'AudioController@getCompleteAudioDetails',
        ],
        'audios_all_read' => [
            'AudioController@postHandleFineUploader',
            'AudioController@postAdd',
            'AudioController@postEdit',
            'AudioController@getInfo',
            'AudioController@postDeleteAction',
            'AudioController@postBulkUpdateStatus',
            'AudioController@getAudioToEdit',
            'AudioController@getCompleteAudioDetails',
        ],
        'artists_all' => [
            'ArtistController@getAritstAudios',
            'ArtistController@getArtistList',
            'ArtistController@getAdd',
            'ArtistController@postAdd',
            'ArtistController@postEdit',
            'ArtistController@postArtistImage',
            'ArtistController@postDeleteArtistImage',
            'ArtistController@getInfo',
        ],
        'artists_all_write' => [
            'ArtistController@getAritstAudios',
            'ArtistController@getArtistList',
            'ArtistController@getAdd',
            'ArtistController@postAdd',
            'ArtistController@postEdit',
            'ArtistController@postArtistImage',
            'ArtistController@postDeleteArtistImage',
            'ArtistController@getInfo',
        ],
        'artists_all_read' => [
            'ArtistController@getAritstAudios',
            'ArtistController@getArtistList',
            'ArtistController@getAdd',
            'ArtistController@postAdd',
            'ArtistController@postEdit',
            'ArtistController@postArtistImage',
            'ArtistController@postDeleteArtistImage',
            'ArtistController@getInfo',
        ],
        'languages_all' => [
            'LanguageController@getAdd',
            'LanguageController@postAdd',
            'LanguageController@postEdit',
            'LanguageController@getInfo',
        ],
        'languages_all_write' => [
            'LanguageController@getAdd',
            'LanguageController@postAdd',
            'LanguageController@postEdit',
            'LanguageController@getInfo',
        ],
        'languages_all_read' => [
            'LanguageController@getAdd',
            'LanguageController@postAdd',
            'LanguageController@postEdit',
            'LanguageController@getInfo',
        ],
        'change_password_all' => [
            'AdminUserController@postChangepassword',
            'AdminUserController@getChangepassword',
            'AdminUserController@getChangePasswordInfo',
        ],
        'ads_all' => [
            'AdsController@getInfo',
            'AdsController@postRecords',
            'AdsController@getVideoToEdit',
            'AdsController@postParentCategory',
            'AdsController@getVideoAds',
            'AdsController@getUpdatedDetails',
            'AdsController@postUpdateStatus',
            'AdsController@postEdit',
            'AdsController@postAction',
            'AdsController@postBulkUpdateStatus',
            'AdsController@postAdd',
            'AdsController@postCategoryImage',
            'AdsController@postDeleteCategoryImage',
            'AdsController@addLanguage',
            'AdsController@getIndex',
            'AdsController@getGridlist',
        ],
        'ads_all_write' => [
            'AdsController@getInfo',
            'AdsController@postRecords',
            'AdsController@getVideoToEdit',
            'AdsController@postParentCategory',
            'AdsController@getVideoAds',
            'AdsController@getUpdatedDetails',
            'AdsController@postUpdateStatus',
            'AdsController@postEdit',
            'AdsController@postAction',
            'AdsController@postBulkUpdateStatus',
            'AdsController@postAdd',
            'AdsController@postCategoryImage',
            'AdsController@postDeleteCategoryImage',
            'AdsController@addLanguage',
            'AdsController@getIndex',
            'AdsController@getGridlist',
        ],
        'ads_all_read' => [
            'AdsController@getInfo',
            'AdsController@postRecords',
            'AdsController@getVideoToEdit',
            'AdsController@postParentCategory',
            'AdsController@getVideoAds',
            'AdsController@getUpdatedDetails',
            'AdsController@postUpdateStatus',
            'AdsController@postEdit',
            'AdsController@postAction',
            'AdsController@postBulkUpdateStatus',
            'AdsController@postAdd',
            'AdsController@postCategoryImage',
            'AdsController@postDeleteCategoryImage',
            'AdsController@addLanguage',
            'AdsController@getIndex',
            'AdsController@getGridlist',
        ],

    ],
];

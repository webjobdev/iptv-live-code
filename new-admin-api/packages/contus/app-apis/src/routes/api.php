<?php

use App\Http\Middleware\authJWT;
use Contus\AppApi\Api\Controllers\App\AppApiController;
use Contus\AppApi\Api\Controllers\App\AuthApiController;
use Contus\AppApi\Api\Controllers\App\UserApiController;

use Contus\AppApi\Api\Controllers\TvApp\AppApiController as TvAppApiController;
use Contus\AppApi\Api\Controllers\TvApp\AuthApiController as TvAppAuthApiController;
use Contus\AppApi\Api\Controllers\TvApp\UserApiController as TvAppUserApiController;

use Contus\AppApi\Api\Controllers\v3\AppApiController as v3ApiController;
use Contus\AppApi\Api\Controllers\v3\AuthApiController as v3AuthApiController;
use Contus\AppApi\Api\Controllers\v3\UserApiController as v3UserApiController;

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/')->namespace('Contus\AppApi\Api\Controllers')->group(function () {
    Route::middleware(['cors'])->group(function () {

        Route::post('verify/provider-id', [AuthApiController::class, 'checkProviderId']);
        Route::post('login', [AuthApiController::class, 'postLogin']);
        Route::get('logout', [AuthApiController::class, 'logout']);

        Route::middleware(['jwt-auth'])->group(function () {

            // drm api
            Route::get('drm/profile/{id}', [AppApiController::class, 'getDrmProfile']);

            // homescreen api
            Route::get('home-screen/banner/slider', [AppApiController::class, 'getBanner']);
            Route::get('home-screen/data', [AppApiController::class, 'getHomeScreen'])->middleware('geo-block');

            // movie list api
            Route::get('movie-list', [AppApiController::class, 'MovieList'])->middleware('geo-block');

            // movie detail api
            Route::get('movie-detail', [AppApiController::class, 'MovieDetail']);
            Route::get('/movie-category', [AppApiController::class, 'MovieCategory']);

            // tvshow season detail api
            Route::get('tvshow/season-detail', [AppApiController::class, 'SeasonDetail']);

            // live event api
            Route::get('/live-channel/fetch', [AppApiController::class, 'fetchLiveChannel']);
            Route::get('/live-channel/fetch-detail', [AppApiController::class, 'fetchLiveChannelDetails']);
            Route::get('/live-channel/categories', [AppApiController::class, 'liveChannelCategories']);
            Route::get('/live-event/detail', [AppApiController::class, 'LiveEventDetail']);

            // check device logins
            Route::post('/device-login/check', [AppApiController::class, 'checkDeviceLogin']);

            // continue_watching api
            Route::post('/continue-watching/add', [AppApiController::class, 'ContinueWatching']);
            Route::get('/fetch/continue-watching', [AppApiController::class, 'fetchContinueWatching']);

            // payment fetch api
            Route::get('/payment-list/fetch', [AppApiController::class, 'getPaymentList']);

            // search record api
            Route::get('/search-record', [AppApiController::class, 'searchRecords']);

            // payment api
            Route::post('/make-payment', [AppApiController::class, 'CreatePayment']);

            // category list
            Route::get('/genres-list', [AppApiController::class, 'getGenresList']);

            // view count api
            Route::post('/view-count', [AppApiController::class, 'viewCount']);

            // view all api
            Route::get('/view-all', [AppApiController::class, 'viewAll']);

            // ----------------------------------- User APIs START ----------------------------------//

            // add & fetch like api
            Route::post('/like/add', [UserApiController::class, 'addToMyLike']);
            Route::get('/like/fetch', [UserApiController::class, 'fetchMyLike']);
            Route::post('/like/remove', [UserApiController::class, 'removeFromMyLike']);

            Route::post('/my-list/add', [UserApiController::class, 'addToMyList']); // add to my-list
            Route::get('/my-list/fetch', [UserApiController::class, 'fetchMyList']); // fetch my-list
            Route::post('/my-list/remove', [UserApiController::class, 'removeFromMyList']);

            Route::get('/user/profile', [UserApiController::class, 'getUserProfile']); // get user profile
            Route::post('/user/profile/update/{id}', [UserApiController::class, 'updateUserProfile']); // update user profile

            Route::get('/user/get-profiles', [UserApiController::class, 'subscriberProfiles']); // get user profiles
            Route::post('/user/create-profile', [UserApiController::class, 'createSubscriberProfiles']); // create user profiles
            Route::post('/user/edit-profiles/{id}', [UserApiController::class, 'editSubscriberProfiles']); // update user profiles

            Route::post('/user/change-username', [UserApiController::class, 'changeUserName']); //chnage user name
            Route::post('/user/change-password', [UserApiController::class, 'changePassword']); //chnage user password

            // ----------------------------------- User APIs END ----------------------------------//

            Route::post('/email-verification', [UserApiController::class, 'emailVerification']); //email verification
            Route::post('/otp-verification', [UserApiController::class, 'otpVerification']); //otp verification
            Route::post('/forgot-password', [UserApiController::class, 'forgotPassword']); //forgot user password
        });
    });
});

Route::prefix('api/v2/')->namespace('Contus\AppApi\Api\Controllers\TvApp')->group(function () {
    Route::middleware(['cors'])->group(function () {

        Route::post('verify/provider-id', [TvAppAuthApiController::class, 'checkProviderId']);
        Route::post('login', [TvAppAuthApiController::class, 'postLogin']);
        Route::get('logout', [TvAppAuthApiController::class, 'logout']);

        Route::middleware(['jwt-auth-tv'])->group(function () {

            // drm api
            Route::get('drm/profile/{id}', [TvAppApiController::class, 'getDrmProfile']);

            // homescreen api
            Route::get('home-screen/banner/slider', [TvAppApiController::class, 'getBanner']);
            Route::get('home-screen/data', [TvAppApiController::class, 'getHomeScreen'])->middleware('geo-block');

            // movie list api
            Route::get('movie-list', [TvAppApiController::class, 'MovieList'])->middleware('geo-block');

            // movie detail api
            Route::get('movie-detail', [TvAppApiController::class, 'MovieDetail']);
            Route::get('/movie-category', [TvAppApiController::class, 'MovieCategory']);

            // tvshow season detail api
            Route::get('tvshow/season-detail', [TvAppApiController::class, 'SeasonDetail']);

            // live event api
            Route::get('/live-channel/fetch', [TvAppApiController::class, 'fetchLiveChannel']);
            Route::get('/live-channel/fetch-detail', [TvAppApiController::class, 'fetchLiveChannelDetails']);
            Route::get('/live-channel/categories', [TvAppApiController::class, 'liveChannelCategories']);


            Route::get('/live-event/detail', [TvAppApiController::class, 'LiveEventDetail']);

            // check device logins
            Route::post('/device-login/check', [TvAppApiController::class, 'checkDeviceLogin']);

            // continue_watching api
            Route::post('/continue-watching/add', [TvAppApiController::class, 'ContinueWatching']);
            Route::post('/continue-watching/remove', [TvAppApiController::class, 'removeContinueWatching']);
            Route::get('/fetch/continue-watching', [TvAppApiController::class, 'fetchContinueWatching']);

            // payment fetch api
            Route::get('/payment-list/fetch', [TvAppApiController::class, 'getPaymentList']);

            // search record api
            Route::get('/search-record', [TvAppApiController::class, 'searchRecords']);

            // payment api
            Route::post('/make-payment', [TvAppApiController::class, 'CreatePayment']);

            // category list
            Route::get('/genres-list', [TvAppApiController::class, 'getGenresList']);

            // view count api
            Route::post('/view-count', [TvAppApiController::class, 'viewCount']);

            // view all api
            Route::get('/view-all', [TvAppApiController::class, 'viewAll']);

            // ----------------------------------- User APIs START ----------------------------------//

            // add & fetch like api
            Route::post('/like/add', [TvAppUserApiController::class, 'addToMyLike']);
            Route::get('/like/fetch', [TvAppUserApiController::class, 'fetchMyLike']);
            Route::post('/like/remove', [TvAppUserApiController::class, 'removeFromMyLike']);

            Route::post('/my-list/add', [TvAppUserApiController::class, 'addToMyList']); // add to my-list
            Route::get('/my-list/fetch', [TvAppUserApiController::class, 'fetchMyList']); // fetch my-list
            Route::post('/my-list/remove', [TvAppUserApiController::class, 'removeFromMyList']);

            Route::get('/user/profile', [TvAppUserApiController::class, 'getUserProfile']); // get user profile
            Route::post('/user/profile/update/{id}', [TvAppUserApiController::class, 'updateUserProfile']); // update user profile

            Route::get('/user/get-profiles', [TvAppUserApiController::class, 'subscriberProfiles']); // get user profiles
            Route::post('/user/create-profile', [TvAppUserApiController::class, 'createSubscriberProfiles']); // create user profiles
            Route::post('/user/edit-profiles/{id}', [TvAppUserApiController::class, 'editSubscriberProfiles']); // update user profiles

            Route::post('/user/change-username', [TvAppUserApiController::class, 'changeUserName']); //chnage user name
            Route::post('/user/change-password', [TvAppUserApiController::class, 'changePassword']); //chnage user password

            // ----------------------------------- User APIs END ----------------------------------//

            Route::post('/email-verification', [TvAppUserApiController::class, 'emailVerification']); //email verification
            Route::post('/otp-verification', [TvAppUserApiController::class, 'otpVerification']); //otp verification
            Route::post('/forgot-password', [TvAppUserApiController::class, 'forgotPassword']); //forgot user password
        });
    });
});

Route::prefix('api/v3/')->namespace('Contus\AppApi\Api\Controllers\v3')->group(function () {
    Route::middleware(['cors'])->group(function () {

        Route::post('verify/provider-id', [v3AuthApiController::class, 'checkProviderId']);
        Route::post('login', [v3AuthApiController::class, 'postLogin']);
        Route::post('device-logout', [v3AuthApiController::class, 'deviceLogout']);
        Route::get('logout', [v3AuthApiController::class, 'logout']);

        Route::get('stream', [v3AuthApiController::class, 'getStream']);

        Route::middleware(['jwt-auth-v3'])->group(function () {

            // drm api
            Route::get('drm/profile/{id}', [v3ApiController::class, 'getDrmProfile']);

            // homescreen api
            Route::get('home-screen/banner/slider', [v3ApiController::class, 'getBanner']);
            Route::get('home-screen/data', [v3ApiController::class, 'getHomeScreen'])->middleware('geo-block');

            // movie list api
            Route::get('movie-list', [v3ApiController::class, 'MovieList'])->middleware('geo-block');

            // movie detail api
            Route::get('movie-detail', [v3ApiController::class, 'MovieDetail']);
            Route::get('/movie-category', [v3ApiController::class, 'MovieCategory']);

            // tvshow season detail api
            Route::get('tvshow/season-detail', [v3ApiController::class, 'SeasonDetail']);

            // live event api
            Route::get('/live-channel/fetch', [v3ApiController::class, 'fetchLiveChannel']);
            Route::get('/live-channel/fetch-detail', [v3ApiController::class, 'fetchLiveChannelDetails']);
            Route::get('/live-channel/categories', [v3ApiController::class, 'liveChannelCategories']);


            Route::get('/live-event/detail', [v3ApiController::class, 'LiveEventDetail']);

            // check device logins
            Route::post('/device-login/check', [v3ApiController::class, 'checkDeviceLogin']);

            // continue_watching api
            Route::post('/continue-watching/add', [v3ApiController::class, 'ContinueWatching']);
            Route::post('/continue-watching/remove', [v3ApiController::class, 'removeContinueWatching']);
            Route::get('/fetch/continue-watching', [v3ApiController::class, 'fetchContinueWatching']);

            // payment fetch api
            Route::get('/payment-list/fetch', [v3ApiController::class, 'getPaymentList']);

            // search record api
            Route::get('/search-record', [v3ApiController::class, 'searchRecords']);

            // payment api
            Route::post('/make-payment', [v3ApiController::class, 'CreatePayment']);

            // category list
            Route::get('/genres-list', [v3ApiController::class, 'getGenresList']);

            // view count api
            Route::post('/view-count', [v3ApiController::class, 'viewCount']);

            // view all api
            Route::get('/view-all', [v3ApiController::class, 'viewAll']);

            // ----------------------------------- User APIs START ----------------------------------//

            // add & fetch like api
            Route::post('/like/add', [v3UserApiController::class, 'addToMyLike']);
            Route::get('/like/fetch', [v3UserApiController::class, 'fetchMyLike']);
            Route::post('/like/remove', [v3UserApiController::class, 'removeFromMyLike']);

            Route::post('/my-list/add', [v3UserApiController::class, 'addToMyList']); // add to my-list
            Route::get('/my-list/fetch', [v3UserApiController::class, 'fetchMyList']); // fetch my-list
            Route::post('/my-list/remove', [v3UserApiController::class, 'removeFromMyList']);

            Route::get('/user/profile', [v3UserApiController::class, 'getUserProfile']); // get user profile
            Route::post('/user/profile/update/{id}', [v3UserApiController::class, 'updateUserProfile']); // update user profile

            Route::get('/user/get-profiles', [v3UserApiController::class, 'subscriberProfiles']); // get user profiles
            Route::post('/user/create-profile', [v3UserApiController::class, 'createSubscriberProfiles']); // create user profiles
            Route::post('/user/edit-profiles/{id}', [v3UserApiController::class, 'editSubscriberProfiles']); // update user profiles

            Route::post('/user/change-username', [v3UserApiController::class, 'changeUserName']); //chnage user name
            Route::post('/user/change-password', [v3UserApiController::class, 'changePassword']); //chnage user password

            // ----------------------------------- User APIs END ----------------------------------//

            Route::post('/email-verification', [v3UserApiController::class, 'emailVerification']); //email verification
            Route::post('/otp-verification', [v3UserApiController::class, 'otpVerification']); //otp verification
            Route::post('/forgot-password', [v3UserApiController::class, 'forgotPassword']); //forgot user password
        });
    });
});


Route::get('get-surat-country', [v3UserApiController::class, 'showSuratCountry']);
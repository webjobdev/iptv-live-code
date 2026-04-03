<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Contus\User\Api\Controllers\Admin\AuthContoller;
use Contus\User\Api\Controllers\Admin\AdminUserController;
use Contus\User\Api\Controllers\Admin\AdminUserGroupController;
use Contus\User\Api\Controllers\RolePermissions\PermissionController;
use Contus\User\Api\Controllers\Admin\SettingsController;

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

// Route::prefix('api/admin')->namespace('Contus\User\Src\Api\Controllers\Admin')->group(function () {
//     Route::group(['middleware' => ['cors']], function () {
//         Route::get('login/info', 'AuthController@getInfo');
//         Route::post('auth/login', 'AuthController@postLogin');
//         Route::get('auth/logout', 'AuthController@logout');
//         Route::get('forgot-password/info', 'AuthController@getforgotPwdInfo');
//         Route::post('auth/forgot-password', 'AuthController@postForgotPassword');

//         Route::group(['middleware' => ['jwt-auth']], function () {
//             /** UserModule Route **/
//             Route::get('users/info', 'AdminUserController@getInfo');
//             Route::post('users/records', 'AdminUserController@postRecords');
//             Route::post('users/add', 'AdminUserController@postAdd');
//             Route::post('users/edit/{id}', 'AdminUserController@postEdit');
//             Route::post('profile/users/edit/{id}', 'AdminUserController@profileUpdate');
//             Route::post('users/action', 'AdminUserController@postAction');
//             Route::post('users/changepassword', 'AdminUserController@postChangepassword');
//             Route::get('users/change-password-info', 'AdminUserController@getChangePasswordInfo');
//             Route::post('users/update-status', 'AdminUserController@postUpdateStatus');

//             /** User Profile Route **/
//             Route::post('users/delete-profile-image/{id}', 'AdminUserController@postDeleteProfileImage');
//             Route::post('users/profile-image', 'AdminUserController@postProfileImage');
//             Route::get('users/edit', 'AdminUserController@getEdit');

//             /** AdminGroups Route **/
//             Route::get('admingroup/info', 'AdminUserGroupController@getInfo');
//             Route::post('groups/records', 'AdminUserGroupController@postRecords');
//             Route::post('groups/action', 'AdminUserGroupController@postAction');
//             Route::post('groups/edit/{id}', 'AdminUserGroupController@postEdit');
//             Route::post('groups/add', 'AdminUserGroupController@postAdd');
//             Route::get('groups/edit_info/{id}', 'AdminUserGroupController@getEditInfo');

//             /** Site Settings Route */
//             Route::get('settings', 'SettingsController@getIndex');
//             Route::get('settings/info', 'SettingsController@getInfo');
//             Route::post('settings/update', 'SettingsController@postUpdate');
//         });
//     });
// });

// dd(1230);



Route::prefix('api/admin')->group(function () {
    Route::middleware(['cors'])->group(function () {

        // Authentication Routes
        Route::get('login/info', [AuthContoller::class, 'getInfo']);
        Route::post('auth/login', [AuthContoller::class, 'postLogin']);
        Route::get('auth/logout', [AuthContoller::class, 'logout']);
        Route::get('forgot-password/info', [AuthContoller::class, 'getforgotPwdInfo']);
        Route::post('auth/forgot-password', [AuthContoller::class, 'postForgotPassword']);

        // Protected Routes (Require JWT Authentication)
        Route::middleware(['jwt-auth'])->group(function () {

            Route::get('token/check', [AdminUserController::class, 'checkToken']);

            /** UserModule Routes **/
            Route::get('users/info', [AdminUserController::class, 'getInfo']);
            Route::post('users/records', [AdminUserController::class, 'postRecords']);
            Route::post('users/add', [AdminUserController::class, 'postAdd']);
            Route::post('users/edit/{id}', [AdminUserController::class, 'postEdit']);
            Route::post('profile/users/edit/{id}', [AdminUserController::class, 'profileUpdate']);
            Route::post('users/action', [AdminUserController::class, 'postAction']);
            Route::post('users/changepassword', [AdminUserController::class, 'postChangepassword']);
            Route::get('users/change-password-info', [AdminUserController::class, 'getChangePasswordInfo']);
            Route::post('users/update-status', [AdminUserController::class, 'postUpdateStatus']);

            /** User Profile Routes **/
            Route::post('users/delete-profile-image/{id}', [AdminUserController::class, 'postDeleteProfileImage']);
            Route::post('users/profile-image', [AdminUserController::class, 'postProfileImage']);
            Route::get('users/edit', [AdminUserController::class, 'getEdit']);


            Route::get('isg/edit', [AdminUserController::class, 'getUserEdit']);

            /** AdminGroups Routes **/
            Route::get('admingroup/info', [AdminUserGroupController::class, 'getInfo']);
            Route::post('groups/records', [AdminUserGroupController::class, 'postRecords']);
            Route::post('groups/action', [AdminUserGroupController::class, 'postAction']);
            Route::post('groups/edit/{id}', [AdminUserGroupController::class, 'postEdit']);
            Route::post('groups/add', [AdminUserGroupController::class, 'postAdd']);
            Route::get('groups/edit_info/{id}', [AdminUserGroupController::class, 'getEditInfo']);

            /** Site Settings Routes **/
            Route::get('settings', [SettingsController::class, 'getIndex']);
            Route::get('settings/info', [SettingsController::class, 'getInfo']);
            Route::post('settings/update', [SettingsController::class, 'postUpdate']);


            /** Assign Permission */
            Route::get('assign-permission', [PermissionController::class, 'assignPermissions']);
        });
    });
});

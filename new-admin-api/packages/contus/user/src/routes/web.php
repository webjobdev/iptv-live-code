<?php

// use Illuminate\Support\Facades\Route;
// use Contus\User\Http\Controllers\Admin\AuthController;
// use Contus\User\Http\Controllers\Admin\AdminUserController;
// use Contus\User\Http\Controllers\Admin\SettingsController;
// use Contus\User\Http\Controllers\Admin\AdminUserGroupController;

// // Redirect root to admin login
// Route::get('/', function () {
//     return redirect('admin');
// });

// // Admin routes
// Route::prefix('admin')->group(function () {

//     // Authentication routes
//     Route::get('/', [AuthController::class, 'getLogin'])->name('admin');
//     Route::post('auth/login', [AuthController::class, 'postLogin'])->name('admin.auth.login.post');
//     Route::get('auth/login', [AuthController::class, 'getLogin'])->name('admin.auth.login.get');
//     Route::post('auth/logout', [AuthController::class, 'logout'])->name('admin.auth.logout.post');
//     Route::get('auth/forgot-password', [AuthController::class, 'getForgotPassword']);
//     Route::post('auth/forgot-password', [AuthController::class, 'postForgotPassword']);
//     Route::get('auth/language/{language}', [AuthController::class, 'switchLanguage']);

//     // Routes with authentication middleware
//     Route::middleware(['auth.admin'])->group(function () {
//         Route::get('users/profile', [AdminUserController::class, 'getProfile']);
//     });

//     // User and settings routes
//     Route::get('users/profile', [AdminUserController::class, 'getProfile']);
//     Route::get('settings', [SettingsController::class, 'getIndex']);
//     Route::post('settings/update', [SettingsController::class, 'postUpdate']);
//     Route::get('users/changepassword', [AdminUserController::class, 'getChangepassword']);

//     // User and Group management
//     Route::get('users', [AdminUserController::class, 'getIndex']);
//     Route::get('users/gridlist', [AdminUserController::class, 'getGridlist']);
//     Route::get('groups', [AdminUserGroupController::class, 'getIndex']);
//     Route::get('groups/gridlist', [AdminUserGroupController::class, 'getGridlist']);
//     Route::get('groups/add', [AdminUserGroupController::class, 'getAdd']);
//     Route::post('groups/add', [AdminUserGroupController::class, 'postAdd']);
//     Route::get('groups/edit/{id}', [AdminUserGroupController::class, 'getEdit']);
//     Route::post('groups/update/{id}', [AdminUserGroupController::class, 'postUpdate']);
// });

// Uncomment if needed for user registration and password reset routes
/*
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.post');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.token');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset.post');
*/

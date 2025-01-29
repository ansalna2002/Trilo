<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ForgetPasswordController;
use App\Http\Controllers\Web\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {

    //login
    Route::get('login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('loginpost', [LoginController::class, 'loginpost'])->name('loginpost');
    
    Route::post('login_sendotp', [LoginController::class, 'login_sendotp'])->name('login_sendotp');
    Route::post('login_verifyotp', [LoginController::class, 'login_verifyotp'])->name('login_verifyotp');


    //forgetpassword
    Route::get('forgot_password', [AdminController::class, 'forgot_password'])->name('forgot_password');
    Route::post('forgot_password', [ForgetPasswordController::class, 'handle'])->name('forgotpassword.handle');
    //admin
    Route::get('change_password/{email}', [AdminController::class, 'change_password'])->name('change_password');
    Route::post('reset_password_update', [ForgetPasswordController::class, 'reset_password_update'])->name('reset_password_update');
       
    // Route::middleware(['adminauth'])->group(function () {
        //dashboard
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        //logout
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        //reset_password
        Route::get('reset_password', [AdminController::class, 'reset_password'])->name('reset_password');
        Route::post('reset_password_handle', [LoginController::class, 'reset_password_handle'])->name('reset_password_handle');
        //notification
        Route::get('add_notification', [AdminController::class, 'add_notification'])->name('add_notification');
        Route::get('notification_delete/{id}', [DashboardController::class, 'notification_delete'])->name('notification_delete');
       
        Route::get('banner_delete/{id}', [DashboardController::class, 'banner_delete'])->name('banner_delete');
        Route::get('add_language', [AdminController::class, 'add_language'])->name('add_language');
       
        Route::get('edit_user', [AdminController::class, 'edit_user'])->name('edit_user');
       
        Route::get('security', [AdminController::class, 'security'])->name('security');
        
        Route::get('transaction', [AdminController::class, 'transaction'])->name('transaction');
        //user_management
        Route::get('user_management', [AdminController::class, 'user_management'])->name('user_management');
        Route::get('view_user/{id}', [AdminController::class, 'view_user'])->name('view_user');
      
       
        Route::post('languages_store', [DashboardController::class, 'languages_store'])->name('languages_store');
        Route::post('notification_post', [DashboardController::class, 'notification_post'])->name('notification_post');
        
        Route::get('profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('profile_update', [LoginController::class, 'profile_update'])->name('profile_update');
       
        Route::get('talktime_management', [AdminController::class, 'talktime_management'])->name('talktime_management');
        Route::get('edit_talktime/{id}', [AdminController::class, 'edit_talktime'])->name('edit_talktime');
        Route::get('talktime_delete/{id}', [DashboardController::class, 'talktime_delete'])->name('talktime_delete');
        Route::post('update_talktime/{id}', [DashboardController::class, 'update_talktime'])->name('update_talktime');
        Route::post('plan_update', [DashboardController::class, 'plan_update'])->name('plan_update');

    });

// });






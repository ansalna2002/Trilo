<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BankController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/migrate', function () {
  Artisan::call('migrate');
});
Route::get('/initial-run', function () {
  Artisan::call('db:seed');
});
Route::get('/clear-cache', function () {
  Artisan::call('optimize');
});
Route::get('/clear-all-cache', function () {
  Artisan::call('optimize:clear');
});
Route::get('/fresh-database', function () {
  Artisan::call('migrate:fresh');
});
Route::get('/', function () {
  return view('welcome');
});


Route::middleware('apikey')->group(function () {

  //login
  Route::post('login', [AuthController::class, 'login']);
  Route::post('login_verify', [AuthController::class, 'login_verify']);
  //get_language
  Route::get('get_language', [ProfileController::class, 'get_language']);
  Route::get('get_voice_prompt', [SubscriptionController::class, 'get_voice_prompt']);
  Route::get('banner', [UserController::class, 'banner']);
  Route::middleware(['userauth'])->group(function () {
    //set_profile
    Route::post('set_profile', [ProfileController::class, 'set_profile']);
    Route::post('set_profile_update', [ProfileController::class, 'set_profile_update']);
    Route::get('get_profile', [ProfileController::class, 'get_profile']);
    //profile_update
    Route::post('profile_update', [ProfileController::class, 'profile_update']);
    Route::get('show_users_profile', [ProfileController::class, 'show_users_profile']);
    //user_follow
    Route::post('user_follow', [UserController::class, 'user_follow']);
    // user_unfollow
    Route::post('user_unfollow', [UserController::class, 'user_unfollow']);
    //get_followed_users
    Route::get('get_followed_users', [UserController::class, 'get_followed_users']);
    //get_all_users
    Route::get('get_all_users', [ProfileController::class, 'get_all_users']);
    // verify_female_voice
    Route::post('verify_voice', [UserController::class, 'verify_voice']);
    //getUserProfile
    Route::get('get_otheruser_profile/{user_id}', [UserController::class, 'get_otheruser_profile']);
    // delete_user
    Route::get('delete_my_account/{user_id}', [UserController::class, 'delete_my_account']);
    //notification
    Route::get('notification', [UserController::class, 'notification']);
    //block_user
    Route::post('block_user', [UserController::class, 'block_user']);
    //unblock_user
    Route::post('unblock_user', [UserController::class, 'unblock_user']);
    //send_message
    Route::post('send_message', [MessageController::class, 'send_message']);
    Route::post('delete_message', [MessageController::class, 'delete_message']);
    Route::post('get_messages', [MessageController::class, 'get_messages']);
    Route::get('show_all_messages', [MessageController::class, 'show_all_messages']);
    
    Route::post('reply_message', [MessageController::class, 'reply_message']);
    Route::post('mark_message_as_read', [MessageController::class, 'mark_message_as_read']);
    //get_blocked_users
    Route::get('get_blocked_list', [UserController::class, 'get_blocked_list']);
    //logout
    Route::post('logout', [AuthController::class, 'logout']);
    //show_plan
    Route::get('show_plan', [SubscriptionController::class, 'show_plan']);
    // active_plan
    Route::post('active_plan', [SubscriptionController::class, 'active_plan']);
    Route::post('select_avatar', [ProfileController::class, 'select_avatar']);
   
    // user_transaction
    Route::post('user_transaction', [SubscriptionController::class, 'user_transaction']);
    // select_language
    Route::post('select_language', [ProfileController::class, 'select_language']);
    Route::post('edit_language', [ProfileController::class, 'edit_language']);
    // get_selectlanguage
    Route::get('my_languages', [ProfileController::class, 'my_languages']);
    Route::get('get_user_transaction', [SubscriptionController::class, 'get_user_transaction']);
    // get_usertalktime_amount
    Route::get('get_usertalktime_amount', [SubscriptionController::class, 'get_usertalktime_amount']);
    //addbank
    Route::post('add_bank', [BankController::class, 'add_bank']);
    // add_kyc
    Route::post('add_kyc', [BankController::class, 'add_kyc']);
    Route::get('show_avatar', [UserController::class, 'show_avatar']);
  
  
  });

});


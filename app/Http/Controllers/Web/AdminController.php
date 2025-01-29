<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }
    public function forgot_password()
    {
        return view('admin.forgetpassword');
    }
    public function security()
    {
        return view('admin.security');
    }
    public function transaction()
    {
        $subscriptions = Transaction::all();
        return view('admin.transaction',compact('subscriptions'));
    }
    public function user_management()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.user_management',compact('users'));
    }
    public function add_language()
    {
        $banners = Language::all();
        return view('admin.add_language', compact('banners'));
    }
    public function add_notification()
    {
        $notifications = Notification::all();
        return view('admin.add_notification', compact('notifications'));
    }
    public function reset_password()
    {
        return view('admin.reset_password');
    }
    public function change_password($email)
    {
        return view('admin.change_password', compact('email'));
    }
    public function dashboard()
    {
        $userCount = User::where('role', 'user')->count();
        return view('admin.dashboard',compact('userCount'));
    }
    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }
    public function edit_user()
    {
        return view('admin.edit_user');
    }
    public function view_user($id)
    {
        $user = User::findOrFail($id); 
        return view('admin.view_user',compact('user'));
    }
    public function talktime_management()
    {
        $datatable=Plan::all();
        return view('admin.talktime_management',compact('datatable'));
    }
    public function edit_talktime($id)
    {
        $talktime=Plan::findOrFail($id);
        return view('admin.edit_talktime',compact('talktime'));
    }
   
}



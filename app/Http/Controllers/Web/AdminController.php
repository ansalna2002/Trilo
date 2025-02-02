<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Plan;
use App\Models\SecurityPrompt;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

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
        $datatable = SecurityPrompt::all();
        return view('admin.security', compact('datatable'));
    }
    public function transaction()
    {
        $subscriptions = Transaction::all();
        return view('admin.transaction', compact('subscriptions'));
    }
    public function user_management()
    {
        $users = User::where('role', 'user')->get();
        $onlineUsers = User::whereNotNull('last_login')
            ->where('last_login', '>=', Carbon::now()->subMinutes(5))
            ->where('role', 'user')
            ->count();
        $dailyUsers = User::whereDate('created_at', Carbon::today())
        ->where('role', 'user')
        ->count();
        $weeklyUsers = User::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
        ->where('role', 'user')
        ->count();
        $monthlyUsers = User::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])
        ->where('role', 'user')
        ->count();
        return view('admin.user_management', compact('users', 'onlineUsers', 'dailyUsers', 'weeklyUsers', 'monthlyUsers'));
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
    public function change_password($email="",$otp="")
    {
        return view('admin.change_password', compact('email','otp'));
    }

    public function forget_password_reset($email="",$otp="")
    {

        return view('admin.forget_password_reset',compact('email','otp'));
    }
    public function dashboard()
    {
        $userCount = User::where('role', 'user')->count();
        $is_activeCount = User::where('is_active', '1')
            ->where('role', 'user')
            ->count();
        $onlineUsers = User::whereNotNull('last_login')
        ->where('role', 'user')
            ->where('last_login', '>=', Carbon::now()->subMinutes(3))
            ->count();
        $dailyUsers = User::whereDate('created_at', Carbon::today())
        ->where('role', 'user')
        ->count();
        $weeklyUsers = User::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
        ->where('role', 'user')
        ->count();
        $monthlyUsers = User::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])
        ->where('role', 'user')
        ->count();

        return view('admin.dashboard', compact('userCount', 'is_activeCount', 'onlineUsers', 'dailyUsers', 'weeklyUsers', 'monthlyUsers'));
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
        $user = User::with('selectedLanguages')->findOrFail($id);
        return view('admin.view_user', compact('user'));
    }
    public function talktime_management()
    {
        $datatable = Plan::all();
        return view('admin.talktime_management', compact('datatable'));
    }
    public function edit_talktime($id)
    {
        $talktime = Plan::findOrFail($id);
        return view('admin.edit_talktime', compact('talktime'));
    }

}



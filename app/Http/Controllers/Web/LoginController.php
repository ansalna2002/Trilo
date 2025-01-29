<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{

    public function reset_password_handle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:6'],
            'new_pwd' => ['required', 'string', 'min:6', 'confirmed'],
            'new_pwd_confirmation' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()
                ->withErrors(['password' => 'The old password is incorrect.'])
                ->withInput();
        }

        $user->password = Hash::make($request->input('new_pwd'));
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Password has been updated successfully.');
    }
    public function profile_update(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|regex:/^[0-9]{10}$/',
        ]);
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'You need to be logged in to update your profile.');
        }

        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'You need to be logged in to update your profile.');
        }
        $user->user_id = $request->user_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }
    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerateToken();
        Log::info('User logged out successfully');
        return redirect()->route('admin.login');
    }
    public function loginpost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login')->withErrors(['password' => 'The credentials do not match our records.']);
        }
    }
    public function login_sendotp(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);
    
            $user = User::where('email', $request->input('email'))->first();
     
       if (!$user || !Hash::check($request->input('password'), $user->password)) {
        return response()->json(['status' => 'error', 'message' => 'Invalid email or password']);
    }
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found']);
            }
    
            $otp                  = "123456";
            $user->otp            = Crypt::encryptString($otp);
            $user->otp_expires_at = now()->addMinutes(1);
            $user->save();
    
         
            Mail::to($user->email)->send(new OtpMail($otp));
    
            return response()->json(['status' => 'success', 'message' => 'OTP sent successfully']);
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while sending OTP. Please try again.']);
        }
    }
    public function login_verifyotp(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'otp'      => 'required|numeric',
                'password' => 'required',
            ]);
    
            $user = User::where('email', $request->input('email'))->first();
    
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
            }
    
            if (now()->greaterThan($user->otp_expires_at)) {
                return response()->json(['status' => 'error', 'message' => 'OTP has expired']);
            }
    
            $decryptedOtp = Crypt::decryptString($user->otp);
            if ($request->input('otp') != $decryptedOtp) {
                return response()->json(['status' => 'error', 'message' => 'Invalid OTP']);
            }

            $user->otp            = null;
            $user->otp_expires_at = null;
            $user->save();
    
            Auth::login($user);
            
            return response()->json(['status' => 'success', 'redirect_url' => route('dashboard')]);
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while verifying OTP. Please try again.']);
        }
    }
}

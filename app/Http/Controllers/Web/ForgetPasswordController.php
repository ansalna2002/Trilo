<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class ForgetPasswordController extends Controller
{
    public function handle(Request $request)
    {
        $action = $request->input('action');
        if ($action === 'send_otp') {
            return $this->sendOtp($request);
        } elseif ($action === 'verify_otp') {
            return $this->verifyOtp($request);
        }
        return redirect()->back()->with('errormessage', 'Invalid action.');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.']);
        }

        $otp = rand(100000, 999999);
        $encryptedOtp = Crypt::encryptString($otp);
        $expiryTime = Carbon::now()->addMinutes(5); 
        $user->otp = $encryptedOtp;
        $user->otp_expires_at = $expiryTime;
        $user->save();

        try {
           // Send the email using the custom mailable
        Mail::to($request->email)->send(new ForgetPasswordMail($otp));

            return response()->json(['status' => 'success', 'message' => 'OTP has been sent to your email.']);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to send OTP. Please try again.']);
        }
    }

    
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'send_otp' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'The OTP is invalid or has expired.']);
        }

        try {
            $decryptedOtp = Crypt::decryptString($user->otp);
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'The OTP is invalid or has expired.']);
        }

        if ($decryptedOtp === $request->send_otp) {
            return redirect()->route('change_password', ['email' => $request->email]);
        } else {
            return back()->withErrors(['otp' => 'The OTP is incorrect.']);
        }
    }

    public function reset_password_update(Request $request)
    {
        Log::info('Reset Password Request Data:', $request->all());
    
        $validated = $request->validate([
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            Log::error('User not found:', ['email' => $request->email]);
            return redirect()->back()->withErrors(['email' => 'User not found.']);
        }
    
        $user->password = Hash::make($request->password);
        $user->save();
    
        Log::info('Password reset successfully for:', ['email' => $request->email]);
    
        return redirect()->route('admin.login')->with('success', 'Password has been successfully reset.');
    }

}

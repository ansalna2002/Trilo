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
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class ForgetPasswordController extends Controller
{
  

    public function forget_sendotp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)
        ->where('role', 'admin')
        ->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.']);
        }

        $otp                  = "123456";
        $encryptedOtp         = Crypt::encryptString($otp);
        $expiryTime           = Carbon::now()->addMinutes(1);
        $user->otp            = $encryptedOtp;
        $user->otp_expires_at = $expiryTime;
        $user->save();

        try {
        Mail::to($request->email)->send(new ForgetPasswordMail($otp));

            return response()->json(['status' => 'success', 'message' => 'OTP has been sent to your email.']);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to send OTP. Please try again.']);
        }
    }

    public function forget_verifyotp(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'send_otp' => 'required|string',
    ]);
    $user = User::where('email', $request->email)
        ->where('otp_expires_at', '>', Carbon::now())
        ->where('role', 'admin')
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
        $email_encoded = base64_encode($request->email);
        $otp_encoded   = base64_encode($request->send_otp);
        return redirect()->route('change_password', [
            'email' => $email_encoded,
            'otp'   => $otp_encoded,
        ]);
    } else {
        return back()->withErrors(['otp' => 'The OTP is incorrect.']);
    }
}

public function reset_password_update(Request $request)
{
    Log::info('Reset Password Request Data:', $request->all());

    try {
        $validated = $request->validate([
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);
        $decodedEmail = base64_decode($request->email);
        $decodedOtp = base64_decode($request->otp);
        Log::info("Decoded Email: $decodedEmail");
        Log::info("Decoded OTP: $decodedOtp");

        $user = User::where('email', $decodedEmail)
                    ->where('otp', (string) $decodedOtp) 
                    ->where('role', 'admin') 
                    ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid OTP or user not found.');
        }
Log::info($user);
        if ($user->otp_expired_at && now()->greaterThan($user->otp_expired_at)) {
            return redirect()->back()->with('error', 'OTP has expired.');
        }
        $user->password = Hash::make($request->password);
        $user->otp = null; 
        $user->save();
        return redirect()->route('admin.login')->with('success', 'Password has been successfully reset.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred. Please try again.');
    }
}



}

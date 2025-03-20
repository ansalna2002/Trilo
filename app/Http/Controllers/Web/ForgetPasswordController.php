<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\BannerImage;
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
  
public function forgot_password()
{
    return view('admin.forgetpassword');
}

public function forget_otp($email = "")
{
    return view('admin.forget_otp', compact('email'));
}

public function change_password($email="",$otp="")
{
    return view('admin.change_password', compact('email','otp'));
}

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

    $otp = rand(100000, 999999); 
    $encryptedOtp = Crypt::encryptString($otp);
    $expiryTime = Carbon::now()->addMinutes(1); 
    $user->otp = $encryptedOtp;
    $user->otp_expires_at = $expiryTime;
    $user->save();

    try {
        Mail::to($request->email)->send(new ForgetPasswordMail($otp));
        return redirect()->route('forget_otp', ['email' => base64_encode($request->email)])->with('success', 'OTP sent successfully.');
    } catch (\Exception $e) {
        Log::error('Failed to send OTP:', ['error' => $e->getMessage()]);
        return response()->json(['status' => 'error', 'message' => 'Failed to send OTP. Please try again.']);
    }
}

public function forget_verifyotp(Request $request)
{
    try {
        $decodedEmail = base64_decode($request->email);
       
        $request->validate([
            'otp' => 'required|string|min:6|max:6',
        ]);

        $user = User::where('email', $decodedEmail)->where('role', 'admin')->first();

        if (!$user) {
            Log::error('User not found for OTP verification', ['email' => $decodedEmail]);
            return redirect()->back()->with('error', 'Invalid User');
        }
        if (Crypt::decryptString($user->otp) !== $request->otp) {
            Log::warning('Invalid OTP entered', ['user_id' => $user->id, 'email' => $decodedEmail, 'entered_otp' => $request->otp]);
            return redirect()->back()->with('error', 'The verification code is incorrect.');
        }

        return redirect()->route('change_password', [
            'email' => $request->email,  
            'otp' => base64_encode($request->otp),
        ])->with('success', 'OTP Verified successfully.');
    } catch (\Exception $e) {
        Log::error('An error occurred in forget_password_verify_otp', [
            'email' => $request->email,
            'error' => $e->getMessage(),
        ]);
        return redirect()->back()->with('error', 'Something went wrong! Please try again.');
    }
}

public function forgot_resend_otp($email = "")
{
    try {
        $decodedEmail = base64_decode($email);
        $user = User::where('email', $decodedEmail)->where('role', 'admin')->first();
        if (!$user) {
            Log::error('User not found in database for resend OTP', ['email' => $decodedEmail]);
            return redirect()->back()->with('error', 'Email not found.');
        }
        if ($user->otp_expires_at && Carbon::now()->greaterThan($user->otp_expires_at)) {
            Log::info('OTP has expired, generating a new one', ['email' => $decodedEmail]);

            $otp = rand(100000, 999999);
            $expiryTime = Carbon::now()->addMinutes(1); 
            $user->otp = Crypt::encryptString($otp);
            $user->otp_expires_at = $expiryTime;
            $user->save();
        } else {
            return redirect()->back()->with('info', 'OTP is still valid, please check your email.');
        }

        Mail::to($decodedEmail)->send(new ForgetPasswordMail($otp));

        return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
    } catch (\Exception $e) {
        Log::error('An error occurred in forgot_password_resend_otp', [
            'email' => $decodedEmail,
            'error' => $e->getMessage(),
        ]);
        return redirect()->back()->with('error', 'Something went wrong! Please try again.');
    }
}

public function reset_password_update(Request $request)
{
    Log::info('Reset Password Request Data:', $request->all());

    $validator = Validator::make($request->all(), [
        'password'              => 'required|string|min:8|confirmed',
        'password_confirmation' => 'required|string|min:8',
        'email'                 => 'required',
        'otp'                   => 'required',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->with('error', $validator->errors()->first());
    }
    try {
        $decodedEmail = base64_decode($request->email);
        $decodedOtp = base64_decode($request->otp);
        $user = User::where('email', $decodedEmail)->where('role', 'admin')->first();
        if (!$user) {
            Log::warning('Admin password reset failed: no account found', ['email' => $decodedEmail]);
            return redirect()->back()->with('error', 'Invalid email or OTP.');
        }
        try {
            $storedOtp = Crypt::decryptString($user->otp);
            if ($storedOtp !== $decodedOtp) {
                Log::warning('Invalid OTP entered for password reset', ['user_id' => $user->id, 'email' => $decodedEmail]);
                return redirect()->back()->with('error', 'The verification code is incorrect.');
            }
        } catch (\Exception $e) {
            Log::error('Error decrypting OTP', ['email' => $decodedEmail, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Invalid OTP.');
        }

        $user->password = Hash::make($request->password);
        $user->otp = null; 
        $user->save();

        return redirect()->route('admin.login')->with('success', 'Password has been successfully reset.');
    } catch (\Exception $e) {
        Log::error('An error occurred while resetting password', ['email' => $request->email, 'error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'An error occurred. Please try again.');
    }
}

public function banner_delete($id)
{
    $banner = BannerImage::find($id);
    if ($banner) {

        if (file_exists(public_path('images/banner/' . $banner->banner_image))) {
            unlink(public_path('images/banner/' . $banner->banner_image));
        }
        $banner->delete();
        return redirect()->back()->with('successmessage', 'Banner deleted successfully.');
    }
    return redirect()->back()->with('error', 'Banner not found.');
}
}

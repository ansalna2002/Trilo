<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
class AuthController extends Controller
{
   
    public function logout(Request $request)
    {
        if (!auth()->guard('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login!',
                'data' => [],
                'code' => 401,
            ], 401);
        }
        try {
            $user = auth()->guard('sanctum')->user();
            $token = $request->bearerToken();

            if ($token) {
                $token = PersonalAccessToken::findToken($token);
                if ($token) {
                    $token->delete();
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully.',
                'code' => 200,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to log out. Please try again.',
                'code' => 500,
            ], 500);
        }
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|digits:10',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Validation errors',
                    'code' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            $user = User::where('phone_number', $request->phone_number)->first();
            if (!$user) {
                do {
                    $letters  = 'TRI';
                    $numbers  = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                    $randomId = strtoupper($letters . $numbers);
                } while (User::where('user_id', $randomId)->exists());
    
                $user               = new User();
                $user->phone_number = $request->phone_number;
                $user->user_id      = $randomId;
                $user->is_active    = 1;
                $user->otp          = Crypt::encrypt(12345); 
                $user->otp_verified = 0;
                $user->last_login   = null;
                $user->save();
            } else {
                $user->update([
                    'otp'          => Crypt::encrypt(12345), 
                    'otp_verified' => 0,
                ]);
            }
    
            return response()->json([
                'status'  => 'success',
                'message' => 'OTP sent successfully',
                'code'    => 200,
                'data'    => ['otp' => 12345, 'phone_number' => $request->phone_number], 
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Error during login: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'data'    => null,
                'message' => 'An error occurred during login.',
                'code'    => 500,
                'errors'  => $e->getMessage(),
            ], 500);
        }
    }
    
    public function login_verify(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp'          => 'required',
                'phone_number' => 'required|digits:10',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'data'    => null,
                    'message' => 'Validation errors',
                    'code'    => 422,
                    'errors'  => $validator->errors(),
                ], 422);
            }
    
            $user = User::where('phone_number', $request->phone_number)->first();
    
            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found.',
                    'code'    => 404,
                    'errors'  => [],
                ], 404);
            }
    
            try {
                $decryptedOtp = Crypt::decrypt($user->otp); 
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid OTP format.',
                    'code'    => 401,
                    'errors'  => [],
                ], 401);
            }
            if ($decryptedOtp == $request->otp) {
                $user->update([
                    'otp_verified' => 1,
                    'otp'          => null, 
                    'last_login'   => now(),
                ]);
                $token         = $user->createToken('MyApp')->plainTextToken;
                $lastLoginTime = $user->last_login;
                $currentTime   = now();
                $is_online     = $lastLoginTime && $lastLoginTime->diffInMinutes($currentTime) < = 3;
                return response()->json([
                    'status'  => 'success',
                    'message' => 'OTP verified successfully',
                    'code'    => 200,
                    'data'    => [
                        'user_id'      => $user->user_id,
                        'phone_number' => $user->phone_number,
                        'is_online'    => $is_online, 
                        'token'        => $token,
                    ],
                ], 200);
            }
    
            return response()->json([
                'status'  => 'error',
                'data'    => null,
                'message' => 'Invalid OTP or Phone Number.',
                'code'    => 401,
                'errors'  => [],
            ], 401);
        } catch (\Exception $e) {
            Log::error('Error during OTP verification: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'data'    => null,
                'message' => 'An error occurred during OTP verification.',
                'code'    => 500,
                'errors'  => $e->getMessage(),
            ], 500);
        }
    }
    
    
}





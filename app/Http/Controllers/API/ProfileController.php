<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ProfileController extends Controller
{
    public function set_profile(Request $request)
    {
        Log::info($request->all());
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please login!',
                    'data' => [],
                    'code' => 401,
                ]);
            }
    
            $user = auth()->guard('sanctum')->user();
    
            $validator = Validator::make($request->all(), [
                'name' => 'string|required',
                'dob' => 'required',
                'gender' => 'in:male,female,transgender|required',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                    'code' => 422
                ], 422);
            }
    
            $imagePath = $user->profile_image;
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
    
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/images/profile');
                    $file->move($destinationPath, $fileName);
                    $imagePath = 'assets/images/profile/' . $fileName;
    
                    if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                        unlink(public_path($user->profile_image));
                    }
                }
            }
    
            $user->update([
                'name'          => $request->name,
                'dob'           => $request->dob,
                'gender'        => $request->gender,
                'profile_image' => $imagePath,
            ]);
    
            $responseData = [
                'user_id'       => $user->user_id,
                'phone_number'  => $user->phone_number,
                'name'          => $user->name,
                'gender'        => $user->gender,
                'dob'           => $user->dob,
                'profile_image' => url($imagePath),
            ];
    
            return response()->json([
                'status'  => 'success',
                'message' => 'Profile updated successfully',
                'code'    => 200,
                'data'    => $responseData,
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Error during profile update: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred during profile update.',
                'errors'  => $e->getMessage(),
                'code'    => 500,
            ], 500);
        }
    }
    public function get_profile()
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Please login!',
                    'data'    => [],
                    'code'    => 401,
                ]);
            }
            $user = auth()->guard('sanctum')->user();
            $responseData = [
                'user_id'       => $user->user_id,
                'name'          => $user->name,
                'gender'        => $user->gender,
                'dob'           => $user->dob,
                'phone_number'  => $user->phone_number,
                'profile_image' => url($user->profile_image),

            ];
            return response()->json([
                'status'  => 'success',
                'message' => 'Profile retrieved successfully',
                'code'    => 200,
                'data'    => $responseData,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error retrieving profile: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while retrieving the profile.',
                'errors'  => $e->getMessage(),
                'code'    => 500,
            ], 500);
        }
    }
    public function set_profile_update(Request $request)
    {
        Log::info($request->all());
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Please login!',
                    'data'    => [],
                    'code'    => 401,
                ]);
            }
            $user = auth()->guard('sanctum')->user();
            $validator = Validator::make($request->all(), [
                'name'          => 'string|required',
                'dob'           => 'required',
                'gender'        => 'in:male,female,transgender|required',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation errors',
                    'errors'  => $validator->errors(),
                    'code'    => 422,
                ], 422);
            }
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
    
                if ($file->isValid()) {
                    $fileName        = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/images/profile');
                    $file->move($destinationPath, $fileName);
                    $imagePath = 'assets/images/profile/' . $fileName;
                    if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                        unlink(public_path($user->profile_image));
                    }
                    $user->profile_image = $imagePath;
                }
            }
            $userId = $user->id;
            $user->update([
                'name'          => $request->name,
                'dob'           => $request->dob,
                'gender'        => $request->gender,
                'profile_image' => $imagePath,
            ]);
            $responseData = [
                'user_id'       => $user->user_id,
                'phone_number'  => $user->phone_number,
                'name'          => $user->name,
                'gender'        => $user->gender,
                'dob'           => $user->dob,
                'profile_image' => url($imagePath),
            ];

            return response()->json([
                'status'  => 'success',
                'message' => 'Profile updated successfully',
                'code'    => 200,
                'data'    => $responseData,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error during profile update: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred during profile update.',
                'errors'  => $e->getMessage(),
                'code'    => 500,
            ], 500);
        }
    }
    public function profile_update(Request $request)
{
    if (!auth('sanctum')->check()) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Please login!',
            'data'    => [],
            'code'    => 401,
        ], 401);
    }

    $user = auth('sanctum')->user();
    $validator = Validator::make($request->all(), [
        'name'          => 'required|string|max:255',
        'gender'        => 'required|string|in:male,female,transgender',
        'dob'           => 'required|date',
        'interest.*'    => 'required|string|max:255',
        'country'       => 'required|string|max:255',
        'language.*'    => 'required|string|max:255',
        'about'         => 'required|string|max:500',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Validation errors occurred.',
            'data'    => $validator->errors(),
            'code'    => 422,
        ], 422);
    }
    $interest       = implode(',', $request->input('interest'));
    $language       = implode(',', $request->input('language'));
    $user->name     = $request->input('name');
    $user->gender   = $request->input('gender');
    $user->dob      = $request->input('dob');
    $user->interest = $interest;
    $user->about    = $request->input('about');
    $user->country  = $request->input('country');
    $user->language = $language;

    if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        if ($file->isValid()) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/images/profile');
            $file->move($destinationPath, $fileName);
            $imagePath = 'assets/images/profile/' . $fileName;
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }
            $user->profile_image = $imagePath;
        }
    }

    $user->save();

    $responseData = [
        'user_id'       => $user->user_id,
        'name'          => $user->name,
        'gender'        => $user->gender,
        'dob'           => $user->dob,
        'interest'      => explode(',', $user->interest), 
        'country'       => $user->country,
        'about'         => $user->about,
        'language'      => explode(',', $user->language), 
        'profile_image' => $user->profile_image ? asset($user->profile_image) : null,
    ];

    return response()->json([
        'status' => 'success',
        'message' => 'Profile updated successfully.',
        'data' => $responseData,
        'code' => 200,
    ], 200);
    }
    public function get_language(Request $request)
    {
        
        $languages = Language::all();
        $languages->transform(function ($language) {
            $language->image_url = asset('storage/images/' . $language->image);
            unset($language->image);
            return $language;
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Languages retrieved successfully.',
            'data' => $languages,
            'code' => 200,
        ], 200);
    }
    public function my_languages()
{
    if (!auth('sanctum')->check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Please login!',
            'data' => [],
            'code' => 401,
        ], 401);
    }
    $user      = auth('sanctum')->user();
    $languages = explode(',', $user->language);
    return response()->json([
        'status' => 'success',
        'message' => 'User languages retrieved successfully.',
        'data' => [
            'user_id'   => $user->user_id,
            'languages' => $languages,
        ],
        'code' => 200,
    ], 200);
    }
    public function show_users_profile(Request $request)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Please login!',
                    'data'    => [],
                    'code'    => 401,
                ]);
            }
            $user = auth()->guard('sanctum')->user();
            return response()->json([
                'success' => true,
                'data'    => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the user profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // public function get_all_users()
    // {
    //     try {
    //         if (!auth()->guard('sanctum')->check()) {
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => 'Please login!',
    //                 'data'    => [],
    //                 'code'    => 401,
    //             ]);
    //         }
    //         $currentUserId = auth()->guard('sanctum')->user()->user_id;
    //         $users         = User::where('user_id', '!=', $currentUserId)
    //         ->where('role', 'user')
    //             ->get()
    //             ->map(function ($user) use ($currentUserId) {
    //                 if ($user->dob) {
    //                     try {
    //                         $dob = Carbon::createFromFormat('d/m/Y', $user->dob);
    //                         $age = $dob->age;
    //                         $user->age = $age;
    //                     } catch (\Exception $e) {
    //                         Log::error("Invalid date format for user {$user->user_id}: " . $user->dob);
    //                         $user->age = null;
    //                     }
    //                 } else {
    //                     $user->age = null;
    //                 }
    //                 $follow = Follow::where('crnt_user_id', $currentUserId)
    //                     ->where('folw_user_id', $user->user_id)
    //                     ->where('is_active', 1)
    //                     ->first();
    //                 $user->is_follow = $follow ? true : false;
    //                 Log::info("User {$user->user_id} follow status: " . ($user->is_follow ? 'Yes' : 'No'));
    //                 $user->is_online = $user->isOnline();
    //                // Get follower count
    //                 $user->follower_count = $user->followingCount(); 

    //                 return $user;
    //             });
    
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Fetched all users successfully.',
    //             'data' => $users,
    //             'code' => 200,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred while fetching the users.',
    //             'error' => $e->getMessage(),
    //             'code' => 500,
    //         ], 500);
    //     }
    // }
    public function select_language(Request $request)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Please login!',
                    'data'    => [],
                    'code'    => 401,
                ], 401);
            }
    
            $user = auth('sanctum')->user();
            $validator = Validator::make($request->all(), [
                'language_name' => 'required', 
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                    'code'    => 422,
                ], 422);
            }
    
            $selectedLanguages = $request->language_name;
            if (is_string($selectedLanguages)) {
                $selectedLanguages = explode(',', $selectedLanguages);
            }
            $selectedLanguages = array_map('trim', $selectedLanguages);
            if (empty($selectedLanguages)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No languages selected.',
                    'code'    => 400,
                ], 400);
            }
            $selectedUser = User::where('user_id', $user->user_id)->first();
            if (!$selectedUser) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found!',
                    'data'    => [],
                    'code'    => 404,
                ], 404);
            }
            $existingLanguages      = !empty($selectedUser->language) ? explode(',', $selectedUser->language) : [];
            $mergedLanguages        = array_unique(array_merge($existingLanguages, $selectedLanguages));
            $selectedUser->language = implode(',', $mergedLanguages);
            $selectedUser->save();
    
            return response()->json([
                'status'  => 'success',
                'message' => 'User language updated successfully!',
                'data'    => [
                    'user_id'  => $selectedUser->user_id,
                    'language' => $mergedLanguages, 
                ],
                'code'    => 200,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update language.',
                'error'   => $e->getMessage(),
                'code'    => 500,
            ], 500);
        }
    }

    public function get_all_users()
{
    try {
        if (!auth()->guard('sanctum')->check()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Please login!',
                'data'    => [],
                'code'    => 401,
            ]);
        }

        $currentUserId = auth()->guard('sanctum')->user()->user_id;
        $users         = User::where('user_id', '!=', $currentUserId)
            ->where('role', 'user')
            ->get()
            ->map(function ($user) use ($currentUserId) {
                if ($user->dob) {
                    try {
                        $dob = Carbon::createFromFormat('d/m/Y', $user->dob);
                        $age = $dob->age;
                        $user->age = $age;
                    } catch (\Exception $e) {
                        Log::error("Invalid date format for user {$user->user_id}: " . $user->dob);
                        $user->age = null;
                    }
                } else {
                    $user->age = null;
                }

                // Get the follow status
                $follow = Follow::where('crnt_user_id', $currentUserId)
                    ->where('folw_user_id', $user->user_id)
                    ->where('is_active', 1)
                    ->first();
                $user->is_follow = $follow ? true : false;
                Log::info("User {$user->user_id} follow status: " . ($user->is_follow ? 'Yes' : 'No'));

                $user->is_online = $user->isOnline();
                $user->follower_count = $user->followersCount(); 

                return $user;
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched all users successfully.',
            'data' => $users,
            'code' => 200,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching the users.',
            'error' => $e->getMessage(),
            'code' => 500,
        ], 500);
    }
}

}
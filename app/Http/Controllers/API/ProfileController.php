<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Language;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{

    public function get_profile()
    {
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
            $responseData = [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'gender' => $user->gender,
                'dob' => $user->dob,
                'phone_number' => $user->phone_number,
                'profile_image' => url($user->profile_image),

            ];
            return response()->json([
                'status' => 'success',
                'message' => 'Profile retrieved successfully',
                'code' => 200,
                'data' => $responseData,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error retrieving profile: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the profile.',
                'errors' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
    // public function set_profile_update(Request $request)
    // {
    //     Log::info($request->all());
    //     try {
    //         $user = auth()->guard('sanctum')->user();
    //         $validator = Validator::make($request->all(), [
    //             'name'      => 'string|required',
    //             'dob'       => 'required',
    //             'gender'    => 'in:male,female,transgender|required',
    //             'avatar_id' => 'nullable|exists:add_avatars,id',
    //             'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Validation errors',
    //                 'errors' => $validator->errors(),
    //                 'code' => 422,
    //             ], 422);
    //         }
    //         $imagePath = $user->profile_image;

    //         if ($request->hasFile('profile_image')) {
    //             $file = $request->file('profile_image');

    //             if ($file->isValid()) {
    //                 $fileName = time() . '_' . $file->getClientOriginalName();
    //                 $destinationPath = public_path('assets/images/profile');
    //                 $file->move($destinationPath, $fileName);
    //                 $imagePath = 'assets/images/profile/' . $fileName;
    //                 if ($user->profile_image && file_exists(public_path($user->profile_image))) {
    //                     unlink(public_path($user->profile_image));
    //                 }
    //                 $user->profile_image = $imagePath;
    //             }
    //         }
    //         $userId = $user->id;
    //         $user->update([
    //             'name' => $request->name,
    //             'dob' => $request->dob,
    //             'gender' => $request->gender,
    //             'profile_image' => $imagePath,
    //         ]);
    //         $responseData = [
    //             'user_id' => $user->user_id,
    //             'phone_number' => $user->phone_number,
    //             'name' => $user->name,
    //             'gender' => $user->gender,
    //             'dob' => $user->dob,
    //             'profile_image' => url($imagePath),
    //         ];

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Profile updated successfully',
    //             'code' => 200,
    //             'data' => $responseData,
    //         ], 200);

    //     } catch (\Exception $e) {
    //         Log::error('Error during profile update: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred during profile update.',
    //             'errors' => $e->getMessage(),
    //             'code' => 500,
    //         ], 500);
    //     }
    // }
   
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

        $user = auth('sanctum')->user();

        $userLanguages = UserLanguage::where('user_id', $user->id)->get();

        if ($userLanguages->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No languages selected.',
                'data' => [],
                'code' => 404,
            ], 404);
        }

        // Prepare the languages array to return
        $languages = [];
        foreach ($userLanguages as $userLanguage) {
            $languages[] = [
                'language_id' => $userLanguage->language_id,
                'language_name' => $userLanguage->language_name,
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User languages retrieved successfully.',
            'data' => [
                'user_id' => $user->user_id,
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
                    'status' => 'error',
                    'message' => 'Please login!',
                    'data' => [],
                    'code' => 401,
                ]);
            }
            $user = auth()->guard('sanctum')->user();
            $languages = UserLanguage::where('user_id', $user->id)
                ->get(['language_id', 'language_name']);
                $profile_image = url($user->profile_image);
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'profile_image'=>$profile_image,
                    'languages' => $languages->isNotEmpty() ? $languages : 'No languages found',
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the user profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function get_language(Request $request)
    {
        $languages = Language::all();
        $languages->transform(function ($language) {
            $imagePath = $language->image;
            if (strpos($imagePath, 'assets/images/banners/') === false) {
                $imagePath = 'assets/images/banners/' . $imagePath;
            }
            $language->image_url = asset($imagePath);
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
    public function get_all_users()
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please login!',
                    'data' => [],
                    'code' => 401,
                ]);
            }
            $currentUserId = auth()->guard('sanctum')->user()->user_id;
            $users = User::where('user_id', '!=', $currentUserId)
                ->where('role', 'user')
                ->get()
                ->map(function ($user) use ($currentUserId) {
                    $selectedLanguages = UserLanguage::where('user_id', $user->id)
                        ->pluck('language_name');

                    if ($user->dob) {
                        try {
                            $dob = Carbon::createFromFormat('d/m/Y', $user->dob);
                            $user->age = $dob->age;
                        } catch (\Exception $e) {
                            Log::error("Invalid date format for user {$user->user_id}: " . $user->dob);
                            $user->age = null;
                        }
                    } else {
                        $user->age = null;
                    }
                    $follow = Follow::where('crnt_user_id', $currentUserId)
                        ->where('folw_user_id', $user->user_id)
                        ->where('is_active', 1)
                        ->first();
                    $user->is_follow = $follow ? true : false;
                    if ($user->last_login) {
                        $lastLoginTime = Carbon::parse($user->last_login);
                        $now = Carbon::now();
                        $user->is_online = $lastLoginTime->diffInMinutes($now) <= 3;
                    } else {
                        $user->is_online = false;
                    }
                    $user->follower_count = Follow::where('folw_user_id', $user->user_id)
                        ->where('is_active', 1)
                        ->count();
                    if ($user->profile_image) {
                        $user->profile_image_url = url($user->profile_image);
                    }
                    $user->languages = $selectedLanguages->toArray();
                    return $user;
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Fetched all users successfully.',
                'data' => [
                    'users' => $users
                ],
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

    public function select_avatar(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login!',
                'data' => [],
                'code' => 401,
            ], 401);
        }

        $user = auth('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors occurred.',
                'data' => $validator->errors(),
                'code' => 422,
            ], 422);
        }

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
            'profile_image' => $user->profile_image ? asset($user->profile_image) : null,
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully.',
            'data' => $responseData,
            'code' => 200,
        ], 200);
    }

    public function set_profile(Request $request)
    {
        Log::info($request->all());

        try {
            $user = auth()->guard('sanctum')->user();
            $languages = is_array($request->language_name)
                ? explode(',', implode(',', $request->language_name))
                : [];

            $validator = Validator::make([
                'name' => $request->name,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'language_name' => $languages,
            ], [
                'name' => 'string|required',
                'dob' => 'required',
                'gender' => 'in:male,female,transgender|required',
                'language_name' => 'required|array',
                'language_name.*' => 'exists:languages,name',
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                    'code' => 422,
                ], 422);
            }
            $languageNames = Language::whereIn('name', $languages)->pluck('name')->toArray();
            $languageString = implode(',', $languageNames);
            $user->update([
                'name' => $request->name,
                'dob' => $request->dob,
                'gender' => $request->gender,
            ]);

            UserLanguage::where('user_id', $user->id)->delete();

            foreach ($languageNames as $language) {
                UserLanguage::create([
                    'user_id' => $user->id,
                    'language_name' => implode(',', $languageNames)
                ]);
            }
            $existingLanguages = UserLanguage::where('user_id', $user->id)->pluck('language_name')->toArray();
            $duplicateLanguages = array_intersect($existingLanguages, $languageNames);

            if (!empty($duplicateLanguages)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The following languages are already selected: ' . implode(', ', $duplicateLanguages),
                    'code' => 409,
                ], 409);
            }
            $responseData = [
                'user_id' => $user->id,
                'phone_number' => $user->phone_number,
                'name' => $user->name,
                'gender' => $user->gender,
                'dob' => $user->dob,
                'language' => $languageString,
            ];

            Log::info('Response Data:', $responseData);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'code' => 200,
                'data' => $responseData,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error during profile update: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during profile update.',
                'errors' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }




    // public function set_profile(Request $request)
    // {
    //     Log::info($request->all());

    //     try {
    //         $user = auth()->guard('sanctum')->user();

    //         $validLanguages = array_map('strtolower', Language::pluck('name')->toArray());

    //         $languages = is_array($request->language_name)
    //             ? array_map('trim', $request->language_name)
    //             : array_map('trim', explode(',', $request->language_name));

    //         $languages = array_map('strtolower', $languages);

    //         $invalidLanguages = array_diff($languages, $validLanguages);
    //         if (!empty($invalidLanguages)) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid language(s) provided: ' . implode(', ', $invalidLanguages),
    //                 'code' => 422,
    //             ], 422);
    //         }

    //         $validator = Validator::make([
    //             'name' => $request->name,
    //             'dob' => $request->dob,
    //             'gender' => $request->gender,
    //             'language_name' => $languages,
    //         ], [
    //             'name' => 'required|string',
    //             'dob' => 'required|date_format:d/m/Y',
    //             'gender' => 'required|in:male,female,transgender',
    //             'language_name' => 'required|array',
    //             'language_name.*' => ['string', 'distinct', Rule::in($validLanguages)],
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Validation errors',
    //                 'errors' => $validator->errors(),
    //                 'code' => 422,
    //             ], 422);
    //         }

    //         // Fetch existing user languages
    //         $existingLanguages = UserLanguage::where('user_id', $user->id)
    //             ->pluck('language_name')
    //             ->toArray();

    //         // Find new languages that are not already stored
    //         $newLanguages = array_diff($languages, $existingLanguages);

    //         if (empty($newLanguages)) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'These languages are already added: ' . implode(', ', $existingLanguages),
    //                 'code' => 409,
    //             ], 409);
    //         }

    //         // Update user details
    //         $user->update([
    //             'name' => $request->name,
    //             'dob' => $request->dob,
    //             'gender' => $request->gender,
    //         ]);

    //         // Insert only new languages
    //         foreach ($newLanguages as $language) {
    //             UserLanguage::create([
    //                 'user_id' => $user->id,
    //                 'language_name' => $language
    //             ]);
    //         }

    //         // Fetch updated user languages
    //         $updatedLanguages = UserLanguage::where('user_id', $user->id)->pluck('language_name')->toArray();

    //         $responseData = [
    //             'user_id' => $user->id,
    //             'phone_number' => $user->phone_number,
    //             'name' => $user->name,
    //             'gender' => $user->gender,
    //             'dob' => $user->dob,
    //             'language' => implode(',', $updatedLanguages),
    //         ];

    //         Log::info('Response Data:', $responseData);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Profile updated successfully',
    //             'code' => 200,
    //             'data' => $responseData,
    //         ], 200);

    //     } catch (\Exception $e) {
    //         Log::error('Error during profile update: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred during profile update.',
    //             'errors' => $e->getMessage(),
    //             'code' => 500,
    //         ], 500);
    //     }
    // }

    public function set_profile_update(Request $request)
{
    Log::info($request->all());

    try {
        $user = auth()->guard('sanctum')->user();

        $validator = Validator::make($request->all(), [
            'name'      => 'string|required',
            'dob'       => 'required',
            'gender'    => 'in:male,female,transgender|required',
            'avatar_id' => 'nullable|exists:add_avatars,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation errors',
                'errors'  => $validator->errors(),
                'code'    => 422,
            ], 422);
        }

        // **Get avatar image using avatar_id**
        if ($request->filled('avatar_id')) {
            $avatar = \App\Models\AddAvatar::find($request->avatar_id);
            if ($avatar) {
                $user->profile_image = $avatar->image; // Set avatar image
            }
        }

        // **Update user details**
        $user->update([
            'name'         => $request->name,
            'dob'          => $request->dob,
            'gender'       => $request->gender,
            'profile_image'=> $user->profile_image,
        ]);

        $responseData = [
            'user_id'      => $user->user_id,
            'phone_number' => $user->phone_number,
            'name'         => $user->name,
            'gender'       => $user->gender,
            'dob'          => $user->dob,
            'profile_image'=> url($user->profile_image),
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
    
    $user = auth('sanctum')->user();
    $validator = Validator::make($request->all(), [
        'name'          => 'required|string|max:255',
        'gender'        => 'required|string|in:male,female,transgender',
        'dob'           => 'required|date',
        'interest.*'    => 'required|string|max:255',
        'country'       => 'required|string|max:255',
        'language.*'    => 'required|string|max:255',
        'about'         => 'required|string|max:500',
        'avatar_id' => 'nullable|exists:add_avatars,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation errors occurred.',
            'data' => $validator->errors(),
            'code' => 422,
        ], 422);
    }
    if ($request->filled('avatar_id')) {
        $avatar = \App\Models\AddAvatar::find($request->avatar_id);
        if ($avatar) {
            $user->profile_image = $avatar->image; // Set avatar image
        }
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
    $user->profile_image;
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

}
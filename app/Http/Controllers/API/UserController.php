<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    public function user_follow(Request $request)
    {

        if (!auth()->guard('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login!',
                'data' => [],
                'code' => 401,
            ]);
        }
        $user = auth()->guard('sanctum')->user();

        $validator = Validator::make(
            $request->all(),
            [
                'folw_user_id' => 'required|exists:users,user_id',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'code' => 400,
            ], 400);
        }


        $crnt_user_id = $user->user_id;
        if ($crnt_user_id == $request->folw_user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot follow yourself.',
                'code' => 400,
            ], 400);
        }

        $existingFollow = Follow::where('crnt_user_id', $crnt_user_id)
            ->where('folw_user_id', $request->folw_user_id)
            ->first();

        if ($existingFollow) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are already following this user.',
                'code' => 400,
            ], 400);
        }

        $follow = Follow::create([
            'crnt_user_id' => $crnt_user_id,
            'folw_user_id' => $request->folw_user_id,
            'is_active' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'You are now following the user.',
            'data' => [
                'user_id' => $crnt_user_id,
                'follow_data' => $follow,
            ],
            'code' => 200,
        ], 200);
    }
    public function user_unfollow(Request $request)
    {
        if (!auth()->guard('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login!',
                'data' => [],
                'code' => 401,
            ]);
        }
        $user = auth()->guard('sanctum')->user();
        $validator = Validator::make(
            $request->all(),
            [
                'folw_user_id' => 'required|exists:users,user_id',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'data' => [],
                'code' => 400,
            ], 400);
        }
        $crnt_user_id = $user->user_id;
        if ($crnt_user_id == $request->folw_user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot unfollow yourself.',
                'data' => [],
                'code' => 400,
            ], 400);
        }
        $existingFollow = Follow::where('crnt_user_id', $crnt_user_id)
            ->where('folw_user_id', $request->folw_user_id)
            ->first();
        if (!$existingFollow) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not following this user.',
                'data' => [],
                'code' => 400,
            ], 400);
        }
        $existingFollow->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'You have unfollowed the user.',
            'data' => [],
            'code' => 200,
        ], 200);
    }
    public function get_otheruser_profile($user_id)
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
            $user = User::where('user_id', $user_id)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                    'data' => [],
                    'code' => 404,
                ]);
            }
            $follow = Follow::where('crnt_user_id', $currentUserId)
                ->where('folw_user_id', $user->user_id)
                ->where('is_active', 1)
                ->first();
            $user->is_follow = $follow ? true : false;
            return response()->json([
                'status' => 'success',
                'message' => 'User profile fetched successfully.',
                'data' => [
                    'user_id'       => $user->user_id,
                    'name'          => $user->name,
                    'phone_number'  => $user->phone_number,
                    'role'          => $user->role,
                    'dob'           => $user->dob,
                    'gender'        => $user->gender,
                    'interest'      => $user->interest,
                    'country'       => $user->country,
                    'language'      => $user->language,
                    'profile_image' => $user->profile_image,
                    'is_follow'     => $user->is_follow,
                    'is_active'     => $user->is_active,
                ],
                'code' => 200,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the user profile.',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
    public function get_followed_users()
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
                ->get()
                ->map(function ($user) use ($currentUserId) {
                    // Check if the current user follows this user
                    $follow = Follow::where('crnt_user_id', $currentUserId)
                        ->where('folw_user_id', $user->user_id)
                        ->where('is_active', 1)
                        ->first();
                    if ($follow) {
                        $user->is_follow = true;
                        return $user;
                    }
                    return null;
                })
                ->filter(function ($user) {
                    return $user !== null;
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Fetched followed users successfully.',
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
    public function delete_my_account($user_id)
    {
        try {

            $authenticatedUser = auth()->guard('sanctum')->user();
            if (!$authenticatedUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not logged in!',
                    'code' => 401,
                ]);
            }
            $user = User::where('user_id', $user_id)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                    'code' => 404,
                ]);
            }
            if ($authenticatedUser->user_id !== $user_id && $authenticatedUser->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have permission to delete this account.',
                    'code' => 403,
                ]);
            }
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'The user account has been deleted successfully.',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the user account.',
                'error' => $e->getMessage(),
                'code' => 500,
            ]);
        }
    }
    public function block_user(Request $request)
    {
        if (!auth()->guard('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login!',
                'data' => [],
                'code' => 401,
            ], 401);
        }

        $user = auth()->guard('sanctum')->user();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id|different:' . $user->user_id,  
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'code' => 400,
            ], 400);
        }

        $crnt_user_id = $user->user_id;
        $userIdToBlock = $request->user_id;

        $existingFollow = Follow::where('crnt_user_id', $crnt_user_id)
            ->where('folw_user_id', $userIdToBlock)
            ->first();

        if ($existingFollow) {
            $existingFollow->update(['is_active' => 0]);  
            $isActive = 0; 
        } else {
            $follow = Follow::create([
                'crnt_user_id' => $crnt_user_id,
                'folw_user_id' => $userIdToBlock,
                'is_active' => 0,  
            ]);
            $isActive = 0; 
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User blocked successfully.',
            'data' => [
                'current_user_id' => $crnt_user_id,    
                'blocked_user_id' => $userIdToBlock,    
                'is_active' => $isActive,                
            ],
            'code' => 200,
        ], 200);
    }
    public function unblock_user(Request $request)
    {
        if (!auth()->guard('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login!',
                'data' => [],
                'code' => 401,
            ], 401);
        }
        $user = auth()->guard('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id|different:' . $user->user_id,
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'code' => 400,
            ], 400);
        }
        $crnt_user_id = $user->user_id;
        $userIdToUnblock = $request->user_id;
        $existingFollow = Follow::where('crnt_user_id', $crnt_user_id)
            ->where('folw_user_id', $userIdToUnblock)
            ->where('is_active', 0)
            ->first();
        if ($existingFollow) {
            $existingFollow->update(['is_active' => 1]);
            $isActive = 1;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not blocked or follow relationship does not exist.',
                'code' => 400,
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'User unblocked successfully.',
            'data' => [
                'current_user_id' => $crnt_user_id,
                'unblocked_user_id' => $userIdToUnblock,
                'is_active' => $isActive,
            ],
            'code' => 200,
        ], 200);
    }
    public function get_blocked_list(Request $request)
{
    if (!auth()->guard('sanctum')->check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Please login!',
            'data' => [],
            'code' => 401,
        ], 401);
    }
    $user = auth()->guard('sanctum')->user();
    $blockedUsers = Follow::where('crnt_user_id', $user->user_id)
        ->where('is_active', 0)
        ->with('followedUser') 
        ->get();
   $response = $blockedUsers->map(function ($follow) {
    if ($follow->followedUser) {
        return $follow->followedUser; 
    }
    return null; 
})->filter();

    return response()->json([
        'status' => 'success',
        'message' => 'Blocked users fetched successfully.',
        'data' => $response,
        'code' => 200,
    ], 200);
    }

    

}










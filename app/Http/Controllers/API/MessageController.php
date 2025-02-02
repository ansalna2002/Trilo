<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class MessageController extends Controller
{

    public function delete_message(Request $request)
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
            $user = auth('sanctum')->user();
            $validator = Validator::make($request->all(), [
                'receiver_id' => 'required|string|exists:users,user_id',
                'message_id' => 'required|integer|exists:messages,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'code' => 422,
                ], 422);
            }

            $message_id = $request->message_id;
            $receiver_id = $request->receiver_id;

            $message = Message::where('id', $message_id)
                ->where('receiver_id', $receiver_id)
                ->first();

            if (!$message) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Message not found.',
                    'data' => [],
                    'code' => 404,
                ]);
            }
            if ($message->sender_id != $user->user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized to delete this message.',
                    'data' => [],
                    'code' => 403,
                ]);
            }
            $message->is_deleted = 1;
            $message->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Message deleted successfully.',
                'data' => [],
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete message.',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
    public function mark_message_as_read(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'receiver_id' => 'required|string|exists:users,user_id',
                'message_id' => 'required|integer|exists:messages,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'code' => 422,
                ], 422);
            }

            $receiver_id = $request->receiver_id;
            $messageId = $request->message_id;
            $message = Message::where('receiver_id', $receiver_id)
                ->where('id', $messageId)
                ->where('is_read', 0)
                ->first();

            if ($message) {
                $message->is_read = 1;
                $message->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Message marked as read successfully.',
                    'data' => $message,
                    'code' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Message not found or already read.',
                    'code' => 404,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch messages.',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
    public function send_message(Request $request)
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
            $user = auth('sanctum')->user();
            $request->validate([
                'receiver_id' => 'required|exists:users,user_id',
                'message' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'voice_record' => 'nullable|max:5120',
            ]);

            $sender_id = $user->user_id;
            $receiver_id = $request->receiver_id;
            $isBlockedBySender = Follow::where('crnt_user_id', $sender_id)
                ->where('folw_user_id', $receiver_id)
                ->where('is_active', 0)
                ->exists();
            $isBlockedByReceiver = Follow::where('crnt_user_id', $receiver_id)
                ->where('folw_user_id', $sender_id)
                ->where('is_active', 0)
                ->exists();
            if ($isBlockedBySender) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have blocked this user, so you don’t need to message them.',
                    'data' => [],
                    'code' => 403,
                ], 403);
            }
            if ($isBlockedByReceiver) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This user has blocked you, so you cannot message them.',
                    'data' => [],
                    'code' => 403,
                ], 403);
            }
            $isFollowing = Follow::where('crnt_user_id', $sender_id)
                ->where('folw_user_id', $receiver_id)
                ->where('is_active', 1)
                ->exists();
            if (!$isFollowing) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Message cannot be sent. You are not allowed to message this user.',
                    'data' => [],
                    'code' => 403,
                ], 403);
            }

            $imageUrl = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file->isValid()) {
                    $imageName = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/images/messages');
                    $file->move($destinationPath, $imageName);
                    $imageUrl = asset('assets/images/messages/' . $imageName);
                }
            }
            $voiceUrl = null;
            if ($request->hasFile('voice_record')) {
                $file = $request->file('voice_record');
                if ($file->isValid()) {
                    $voiceName = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/voices/messages');
                    $file->move($destinationPath, $voiceName);
                    $voiceUrl = asset('assets/voices/messages/' . $voiceName);
                }
            }

            $message = Message::create([
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'message' => $request->message,
                'image' => $imageUrl,
                'voice_record' => $voiceUrl,
                'is_read' => 0,
                'is_deleted' => 0,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully.',
                'data' => $message,
                'code' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send message.',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
    public function reply_message(Request $request)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please login!',
                    'data' => [],
                    'code' => 401,
                ], 401);
            }
            $user = auth('sanctum')->user();
            $validator = Validator::make($request->all(), [
                'receiver_id' => 'required|string|exists:users,user_id',
                'message_id' => 'required|integer|exists:messages,id',
                'reply_message' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'code' => 422,
                ], 422);
            }
            $message_id = $request->message_id;
            $receiver_id = $request->receiver_id;
            $reply_text = $request->reply_message;
            $originalMessage = Message::where('id', $message_id)
                ->where('receiver_id', $user->user_id)
                ->first();
            if (!$originalMessage) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Message not found or not authorized to reply.',
                    'data' => [],
                    'code' => 404,
                ], 404);
            }
            $reply = Message::create([
                'sender_id' => $user->user_id,
                'receiver_id' => $originalMessage->sender_id,
                'message' => $reply_text,
                'is_read' => 0,
                'is_deleted' => 0,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Reply sent successfully.',
                'data' => $reply,
                'code' => 201,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send reply.',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
    public function get_messages(Request $request)
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
            $user = auth('sanctum')->user();
            $validator = Validator::make($request->all(), [
                'receiver_id' => 'required|string|exists:users,user_id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'code' => 422,
                ], 422);
            }
            $receiver_id = $request->receiver_id;
            if (!$receiver_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Receiver ID is required.',
                    'data' => [],
                    'code' => 400,
                ]);
            }

            $messages = Message::whereIn('sender_id', [$user->user_id, $receiver_id])
                ->whereIn('receiver_id', [$user->user_id, $receiver_id])
                ->whereColumn('sender_id', '!=', 'receiver_id')
                ->get();

            foreach ($messages as $message) {
                if ($message->image) {
                    $message->image_url = ($message->image);
                }
                if ($message->voice_record) {
                    $message->voice_url = ($message->voice_record);
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Messages fetched successfully.',
                'data' => $messages,
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch messages.',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }


}




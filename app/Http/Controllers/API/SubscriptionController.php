<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function show_plan()
    {
        try {
            $user = auth()->guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not logged in!',
                    'code' => 401,
                ]);
            }
            $show_plan = Plan::latest()->get();
            return response()->json([
                'status' => 'success',
                'message' => 'show_plan fetched successfully.',
                'data' => $show_plan,
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching notifications.',
                'error' => $e->getMessage(),
                'code' => 500,
            ]);
        }
    }
    public function user_transaction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plan_id' => 'required|exists:plans,id',
                'amount' => 'required|numeric',
                'transaction_id' => 'required|string',
                'plan_name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'code' => 400,
                ], 400);
            }
            $user = auth()->guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not logged in!',
                    'code' => 401,
                ], 401);
            }
            $plan = Plan::find($request->plan_id);
            if (!$plan || $plan->plan !== $request->plan_name) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid plan name for the provided plan ID.',
                    'code' => 400,
                ], 400);
            }
            $existingSubscription = PlanSubscription::where('user_id', $user->id)
                ->where('plan_id', $request->plan_id)
                ->where('status', '1')
                ->first();

            if ($existingSubscription) {
                $subscribedDate = Carbon::parse($existingSubscription->subscribed_date);
                $subscriptionEndDate = $subscribedDate->addDays($existingSubscription->available_days);
                if (Carbon::now()->lt($subscriptionEndDate)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You already have an active subscription to this plan.',
                        'code' => 400,
                    ], 400);
                } else {
                    $existingSubscription->status = '0';
                    $existingSubscription->is_subscribed = '0';
                    $existingSubscription->save();

                    $user->is_subscriber = 0; 
                    $user->save();
                }
            }

            $transaction                 = new Transaction();
            $transaction->user_id        = $user->user_id;
            $transaction->name           = $user->name;
            $transaction->number         = $user->phone_number;
            $transaction->plan_id        = $request->plan_id;
            $transaction->amount         = $plan->amount;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->status         = '1';                       
            $transaction->save();

            $subscription                  = new PlanSubscription();
            $subscription->user_id         = $user->id;
            $subscription->plan_id         = $request->plan_id;
            $subscription->plan_name       = $plan->plan;
            $subscription->amount          = $plan->amount;
            $subscription->available_days  = $plan->available_days;
            $subscription->subscribed_date = now();
            $subscription->talk_time       = $plan->talk_time;
            $subscription->type            = $plan->type;
            $subscription->status          = '1';
            $subscription->is_subscribed   = '1';
            $subscription->remark          = $request->remark ?? null;
            $subscription->save();

            $user->is_subscriber = 1;
            $user->type=$plan->type;
            $user->subscribed_date = now();
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Subscribed and activated successfully!',
                'data' => [
                    'transaction' => $transaction,
                    'subscription' => $subscription,
                ],
                'code' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
    public function get_user_transaction(Request $request)
    {
        try {
            // Authenticate the user
            $user = auth()->guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not logged in!',
                    'code' => 401,
                ], 401);
            }
    
            // Retrieve all transactions for the user
            $transactions = Transaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc') // Order by most recent first
                ->get();
    
            // Retrieve the most recent active subscription
            $subscription = PlanSubscription::where('user_id', $user->id)
                ->where('status', '1') // Active subscriptions
                ->latest()
                ->first();
    
            // Check if subscription exists
            if (!$subscription) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No active subscription found for this user.',
                    'code' => 404,
                ], 404);
            }
    
            // Check if there are transactions
            if ($transactions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No transactions found for this user.',
                    'code' => 404,
                ], 404);
            }
    
            // Prepare the response data
            $data = $transactions->map(function($transaction) use ($subscription) {
                return [
                    'plan_name' => $subscription->plan_name,
                    'user_id' => $transaction->user_id,
                    'talk_time' => $subscription->talk_time,
                    'available_days' => $subscription->available_days,
                    'amount' => $transaction->amount,
                    'plan_id' => $subscription->plan_id,
                    'transaction_id' => $transaction->transaction_id,
                    'created_date' => $transaction->created_at,
                ];
            });
    
            return response()->json([
                'status' => 'success',
                'message' => 'Transaction details retrieved successfully.',
                'data' => $data,
                'code' => 200,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }

}

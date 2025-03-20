<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AddBank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function add_bank(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            $request->validate([
                'account_holder_name'    => 'required|string|max:255',
                'account_number'         => 'required|string|max:20',
                'confirm_account_number' => 'required|string|same:account_number',
                'ifsc_code'              => 'required|string|max:20',
                'bank_name'              => 'required|string|max:255',
            ]);

         
        $bank                         = new AddBank();
        $bank->user_id                = $user->user_id;
        $bank->account_holder_name    = $request->account_holder_name;
        $bank->account_number         = $request->account_number;
        $bank->ifsc_code              = $request->ifsc_code;
        $bank->bank_name              = $request->bank_name;
        $bank->save();
          
            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully.',
                'data' => $bank,
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
    public function add_kyc(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            $request->validate([
                'pan_number' => 'required|string|max:255',
            ]);
            $bank = AddBank::where('user_id', $user->user_id)->first();
    
            if ($bank) {
                $bank->pan_number = $request->pan_number;
                $bank->save();
                $message = 'KYC details updated successfully.';
            } else {
                $bank             = new AddBank();
                $bank->user_id    = $user->user_id;
                $bank->pan_number = $request->pan_number;
                $bank->save();
                $message = 'KYC details added successfully.';
            }
    
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $bank,
                'code' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update KYC details.',
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }
    

}

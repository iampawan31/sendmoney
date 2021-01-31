<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhoneVerificationController extends Controller
{
    /**
     * getOTP
     *
     * @return void
     */
    public function getOTP()
    {
        if (auth()->user()->phone_verified_at !== null) {
            return response()->json('Phone number verification already completed.', 422);
        }

        // For Demo Purpose Using 123456 as OTP
        $otp = '123456';
        $user = User::find(auth()->id());
        $user->phone_otp = $otp;
        $user->save();

        return response()->json('OTP sent successfully to registered phone number.', 200);
    }

    /**
     * verifyOTP
     *
     * @param  mixed $request
     * @return void
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|size:6',
        ]);

        if (auth()->user()->phone_otp === $request->one_time_password) {
            $user = User::find(auth()->user()->id);

            $user->phone_verified_at = now();
            $user->phone_otp = null;
            $user->save();

            return response()->json('Phone number verified successfully.', 200);
        } else {
            return response()->json('Invalid OTP. Please submit the correct OTP', 422);
        }
    }
}

<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class VerificationController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['verify']);
    }

    /**
     * verify
     *
     * @param  mixed $user_id
     * @param  mixed $request
     * @return void
     */
    public function verify($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "URL Expired. Please Try Again!!"], 401);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response()->json('Email Verified Successfull', 200);
    }

    /**
     * resend
     *
     * @return void
     */
    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return response()->json(["msg" => "Email already verified."], 400);
        }

        auth()->user()->sendEmailVerificationNotification();

        return response()->json(["msg" => "Email verification link sent on your email id"]);
    }
}

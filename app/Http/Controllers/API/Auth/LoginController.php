<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required_without:phone|exists:users,email',
            'phone' => 'required_without:email|exists:users,phone',
            'password' => 'required'
        ]);

        if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        } else {
            $user = User::where('phone', $request->phone)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name ? $request->device_name : 'Device')->plainTextToken;
    }
}

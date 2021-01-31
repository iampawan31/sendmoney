<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15',
            'last_name' => 'required|min:3|max:15',
            'email' => 'required|email|unique:users,email',
            'image' => 'required|file|max:5000|mimes:jpg,png',
            'phone' => 'required|size:10|unique:users,phone',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        $path = $request->file('image')->store('photos');

        $user = User::create([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "phone" => $request->phone,
            'image' => $path,
            "password" => Hash::make($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json(['token' => $user->createToken('Device')->plainTextToken], 201);
    }
}

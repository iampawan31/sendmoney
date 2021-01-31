<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * details
     *
     * @return void
     */
    public function details()
    {
        return response()->json(['user' => User::find(auth()->user()->id)], 200);
    }
}

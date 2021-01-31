<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * getWalletDetails
     *
     * @return void
     */
    public function getWalletDetails()
    {
        return response()->json(['transactions' => auth()->user()->transactions, 'balance' => auth()->user()->balance], 200);
    }
}

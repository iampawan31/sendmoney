<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wallet;

class TransactionController extends Controller
{
    /**
     * storeTransaction
     *
     * @param  mixed $request
     * @return void
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'paying_to' => 'required|exists:users,phone',
            'description' => 'max:30'
        ]);

        $payByUser = User::find(auth()->user()->id);
        $payingToUser = User::wherePhone($request->paying_to)->first();

        if ($payByUser->isUserVerified() && $payingToUser->isUserVerified()) {
            try {
                if ($payByUser->allowWithdraw($request->amount)) {
                    $debitTransaction = Wallet::create([
                        'user_id' => auth()->user()->id,
                        'amount' => $request->amount,
                        'type' => 'debit',
                        'description' => $request->description,
                        'status' => 1

                    ]);

                    $creditTransaction = Wallet::create([
                        'user_id' => $payingToUser->id,
                        'amount' => $request->amount,
                        'type' => 'credit',
                        'description' => $request->description,
                        'status' => 1

                    ]);

                    return response()->json('Amount transfered successfully', 200);
                } else {
                    return response()->json('Wallet balance is insufficient.', 422);
                }
            } catch (\Throwable $th) {
                return response()->json($th, 500);
            }
        } else {
            return response()->json('Please verify your account before transfering money', 422);
        }
    }
}

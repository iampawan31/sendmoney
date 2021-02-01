<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactionOne = Wallet::create([
            'user_id' => User::find(1)->id,
            'amount' => 2000,
            'type' => 'credit',
            'status' => 1,
            'description' => 'Initial Top Up',
        ]);

        $transactionTwo = Wallet::create([
            'user_id' => User::find(1)->id,
            'amount' => 20,
            'type' => 'credit',
            'status' => 1,
            'description' => 'Top Up',
        ]);

        $transactionThree = Wallet::create([
            'user_id' => User::find(2)->id,
            'amount' => 1000,
            'type' => 'credit',
            'status' => 1,
            'description' => 'Initial Top Up',
        ]);

        $transactionThree = Wallet::create([
            'user_id' => User::find(2)->id,
            'amount' => 300,
            'type' => 'debit',
            'status' => 1,
            'description' => 'Transfer to 8123565698',
        ]);

        $transactionThree = Wallet::create([
            'user_id' => User::find(1)->id,
            'amount' => 300,
            'type' => 'credit',
            'status' => 1,
            'description' => 'Transfer to 8123565698',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verified User 1
        User::create([
            'first_name' => 'Pawan',
            'last_name' => 'Kumar',
            'email' => 'user1@sendmoney.com',
            'password' => Hash::make('user123'),
            'phone' => '8123565698',
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        // Verified User 1
        User::create([
            'first_name' => 'Ricky',
            'last_name' => 'Kumar',
            'email' => 'user2@sendmoney.com',
            'password' => Hash::make('user123'),
            'phone' => '8123565699',
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
        ]);
    }
}

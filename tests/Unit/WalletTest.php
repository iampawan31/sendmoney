<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Wallet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class WalletTest extends TestCase
{
    use RefreshDatabase;
    protected $authenticatedVerifiedUser;
    protected $authenticatedNonVerifiedUser;
    protected $registeredAndVerifiedUser;
    protected $registeredAndNonVerifiedUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticatedVerifiedUser = User::factory()
            ->create([
                'phone_verified_at' => now(),
                'email_verified_at' => now()
            ]);

        $this->authenticatedNonVerifiedUser = User::factory()->create();

        $this->registeredAndVerifiedUser = User::factory()
            ->create([
                'phone_verified_at' => now(),
                'email_verified_at' => now()
            ]);

        $this->registeredAndNonVerifiedUser = User::factory()->create();
    }

    /** @test */
    public function an_authenticated_and_verified_user_can_transfer_money_to_registered_and_verified_user()
    {
        $this->withoutExceptionHandling();

        $wallet = Wallet::factory()->create([
            'user_id' => $this->authenticatedVerifiedUser->id,
            'amount' => 4000,
            'type' => 'credit',
            'status' => 1
        ]);

        Sanctum::actingAs($this->authenticatedVerifiedUser, ["*"]);
        $this->postJson(route('api.user.wallet.store-transaction'), [
            'amount' => 100,
            'paying_to' => $this->registeredAndVerifiedUser->phone,
        ])
            ->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_and_non_verified_user_cannot_transfer_money_to_registered_and_verified_user()
    {
        $this->withoutExceptionHandling();

        $wallet = Wallet::factory()->create([
            'user_id' => $this->authenticatedVerifiedUser->id,
            'amount' => 4000,
            'type' => 'credit',
            'status' => 1
        ]);

        Sanctum::actingAs($this->authenticatedNonVerifiedUser, ["*"]);
        $this->postJson(route('api.user.wallet.store-transaction'), [
            'amount' => 100,
            'paying_to' => $this->registeredAndVerifiedUser->phone,
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function an_authenticated_and_verified_user_cannot_transfer_money_to_registered_and_verified_user_with_low_balance()
    {
        $this->withoutExceptionHandling();

        $wallet = Wallet::factory()->create([
            'user_id' => $this->authenticatedVerifiedUser->id,
            'amount' => 1000,
            'type' => 'credit',
            'status' => 1
        ]);

        Sanctum::actingAs($this->authenticatedNonVerifiedUser, ["*"]);
        $this->postJson(route('api.user.wallet.store-transaction'), [
            'amount' => 2000,
            'paying_to' => $this->registeredAndVerifiedUser->phone,
        ])
            ->assertStatus(422);
    }
}

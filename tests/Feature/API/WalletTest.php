<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class WalletTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $wallet;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->wallet = Wallet::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function can_get_wallet_information_for_authenticated_user()
    {
        Sanctum::actingAs($this->user, ["*"]);

        $this->getJson(route('api.user.wallet.details'))
            ->assertStatus(200)
            ->assertJsonCount(2);
    }
}

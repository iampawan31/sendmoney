<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'first_name' => 'Pawan',
            'last_name' => 'Kumar',
            'email' => 'surpawan@gmail.com',
            'phone' => '8123565698',
            'image' => 'http://dummyurl/image'

        ]);
    }

    /** @test */
    public function user_can_get_wallet_transactions()
    {
        Sanctum::actingAs($this->user, ["*"]);

        $this->getJson(route('api.user.details'))
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertSeeText('transactions');
    }
}

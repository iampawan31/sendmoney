<?php

namespace Tests\Feature\API\Authentication;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCanVerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_request_for_email_verification()
    {
        Sanctum::actingAs($this->user, ['*']);
        $this->getJson(route('verification.resend'))->assertStatus(200);
    }
}

<?php

namespace Tests\Feature\API\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserCanVerifyPhoneTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
    }

    /** @test */
    public function user_can_request_otp()
    {
        $this->withoutExceptionHandling();
        $this->getJson(route('api.auth.phone.request-otp'))
            ->assertStatus(200)
            ->assertSeeText('OTP sent successfully to registered phone number.');
    }

    /** @test */
    public function user_can_verify_phone_number_using_otp()
    {
        $this->user->phone_otp = '123456';
        $this->user->save();

        $this->postJson(route('api.auth.phone.verify-otp'), [
            'one_time_password' => '123456'
        ])
            ->assertStatus(200)
            ->assertSeeText('Phone number verified successfully.');

        $this->assertNull($this->user->fresh()->phone_otp);
        $this->assertNotNull($this->user->fresh()->phone_verified_at);
    }

    /** @test */
    public function user_cannot_verify_phone_number_using_wrong_otp()
    {
        $this->user->phone_otp = '123455';

        $this->postJson(route('api.auth.phone.verify-otp'), [
            'one_time_password' => '123456'
        ])
            ->assertStatus(422)
            ->assertSeeText('Invalid OTP. Please submit the correct OTP');

        $this->assertNotNull($this->user->phone_otp);
        $this->assertNull($this->user->phone_verified_at);
    }


    /** @test */
    public function user_cannot_verify_phone_number_again()
    {
        $this->user->phone_verified_at = now();
        $this->user->save();

        $this->getJson(route('api.auth.phone.request-otp'))
            ->assertStatus(422)
            ->assertSeeText('Phone number verification already completed.');
    }
}

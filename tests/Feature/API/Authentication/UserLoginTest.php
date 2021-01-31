<?php

namespace Tests\Feature\API\Authentication;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginTest extends TestCase
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
    public function user_can_login_using_email_and_password()
    {
        $this->postJson(route('api.auth.login'), ['email' => 'surpawan@gmail.com', 'password' => 'pawan123'])
            ->assertStatus(200);
    }

    /** @test */
    public function user_without_valid_credentials_cannot_login()
    {
        $this->postJson(route('api.auth.login'), ['email' => 'surpawan@gmail.com', 'password' => 'pawan'])
            ->assertStatus(422);
    }

    /** @test */
    public function user_can_login_using_phone_and_password()
    {
        $this->postJson(route('api.auth.login'), ['phone' => '8123565698', 'password' => 'pawan123'])
            ->assertStatus(200);
    }

    /** @test */
    public function user_without_valid_credentials_cannot_login_phone()
    {
        $this->postJson(route('api.auth.login'), ['phone' => '8123565698', 'password' => 'pawan'])
            ->assertStatus(422);
    }
}

<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Wallet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $wallet;

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

        $this->wallet = Wallet::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function test_user_has_first_name()
    {
        $this->assertEquals($this->user->first_name, 'Pawan');
    }

    /** @test */
    public function test_user_has_last_name()
    {
        $this->assertEquals($this->user->last_name, 'Kumar');
    }

    /** @test */
    public function test_user_has_an_email()
    {
        $this->assertEquals($this->user->email, 'surpawan@gmail.com');
    }

    /** @test */
    public function test_user_has_a_phone()
    {
        $this->assertEquals($this->user->phone, '8123565698');
    }

    /** @test */
    public function test_user_has_an_image()
    {
        $this->assertEquals($this->user->image, 'http://dummyurl/image');
    }
}

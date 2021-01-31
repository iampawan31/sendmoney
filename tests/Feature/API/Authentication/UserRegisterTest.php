<?php

namespace Tests\Feature\API\Authentication;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();
    }

    /** @test */
    public function user_can_register()
    {
        $this->withoutExceptionHandling();

        $this->postJson(route('api.auth.register'), [
            'first_name' => 'Pawan',
            'last_name' => 'Kumar',
            'phone' => '8123565698',
            'image' => $file = UploadedFile::fake()->image('photo1.jpg'),
            'email' => 'surpawan@gmail.com',
            'password' => 'pawan123',
            'confirm_password' => 'pawan123'
        ])
            ->assertStatus(201)
            ->assertJsonCount(1);

        $filePath = 'photos/' . $file->hashName();

        Storage::disk()->assertExists($filePath);

        $this->assertDatabaseHas('users', [
            'first_name' => 'Pawan',
            'last_name' => 'Kumar',
            'phone' => '8123565698',
            'email' => 'surpawan@gmail.com',
        ]);
    }
}

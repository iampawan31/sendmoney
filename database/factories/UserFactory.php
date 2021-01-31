<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->unique()->numberBetween(1111111111, 9999999999),
            'image' => $this->faker->imageUrl(640, 480),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('pawan123'), // password
            'remember_token' => Str::random(10),
        ];
    }
}

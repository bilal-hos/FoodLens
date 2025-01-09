<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Faker\Core\Number;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory

{

    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'phone_number' =>fake()->phoneNumber(),
            'age' =>fake()->numberBetween(15, 85),
            'weight' => fake()->numberBetween(50, 120),
            'height' => fake()->numberBetween(150, 200),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'address' =>fake()->sentence(),
        ];
    }
}

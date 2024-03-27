<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->userName,
            'website' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'auth_token' => Str::random(60),
        ];
    }
}

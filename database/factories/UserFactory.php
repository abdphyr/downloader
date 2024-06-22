<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'firstname' => fake()->name(),
            'lastname' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'password' => 'password',
        ];
    }
}

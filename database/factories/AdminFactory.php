<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class AdminFactory extends Factory
{

    protected static ?string $password;


    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'family' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'mobile' => $this->faker->numerify('09#########'),
            'password' => null,
            'is_active' => true,
        ];
    }
}

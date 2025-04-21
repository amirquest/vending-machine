<?php

namespace Database\Factories;

use App\Enums\VendingMachineStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;


class VendingMachineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement(VendingMachineStatusEnum::toArray()),
            'healthy_retry_interval' => null,
            'unhealthy_count' => 0
        ];
    }
}

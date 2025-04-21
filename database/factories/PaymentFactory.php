<?php

namespace Database\Factories;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'request_code' => Str::ulid()->toString(),
            'amount' => 1,
            'status' => $this->faker->randomElement(PaymentStatusEnum::toArray()),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn(array $attr) => [
            'status' => PaymentStatusEnum::PAID(),
        ]);
    }

    public function returned(): static
    {
        return $this->state(fn(array $attr) => [
            'status' => PaymentStatusEnum::RETURNED(),
        ]);
    }
}

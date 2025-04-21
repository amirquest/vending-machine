<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\VendingMachine;
use Illuminate\Database\Eloquent\Factories\Factory;


class InventoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'vending_machine_id' => VendingMachine::factory(),
            'quantity' => random_int(1, 100),
        ];
    }

    public function forItem(Item $item): static
    {
        return $this->state(fn(array $attr) => [
            'item_id' => $item->id,
        ]);
    }

    public function forVendingMachine(VendingMachine $vendingMachine): static
    {
        return $this->state(fn(array $attr) => [
            'vending_machine_id' => $vendingMachine->id,
        ]);
    }
}

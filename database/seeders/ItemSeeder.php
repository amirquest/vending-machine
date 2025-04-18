<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getItems() as $item) {
            Item::query()->updateOrCreate(
                [
                    'slug' => $item['slug']
                ],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                ]
            );
        }
    }

    private function getItems(): array
    {
        return [
            [
                'slug' => 'coffee',
                'name' => 'Coffee',
            ],
            [
                'slug' => 'soda',
                'name' => 'Soda',
            ],
        ];
    }
}

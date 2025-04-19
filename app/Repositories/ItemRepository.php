<?php

namespace App\Repositories;

use App\Models\Item;
use App\Repositories\Contracts\AbstractRepository;

class ItemRepository extends AbstractRepository
{

    protected function instance(array $attributes = []): Item
    {
        return new Item($attributes);
    }
}

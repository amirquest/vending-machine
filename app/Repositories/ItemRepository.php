<?php

namespace App\Repositories;

use App\Models\Item;
use App\Repositories\Contracts\AbstractRepository;
use Illuminate\Database\Eloquent\Collection;

class ItemRepository extends AbstractRepository
{
    public function getAll(): Collection
    {
        return $this->getQuery()->get();
    }
    protected function instance(array $attributes = []): Item
    {
        return new Item($attributes);
    }
}

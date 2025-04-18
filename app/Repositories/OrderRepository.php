<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\AbstractRepository;

class OrderRepository extends AbstractRepository
{

    protected function instance(array $attributes = []): Order
    {
        return new Order($attributes);
    }
}

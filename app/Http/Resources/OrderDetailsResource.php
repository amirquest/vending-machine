<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Order $item */
        $item = $this->resource;

        return [
            'status' => $item->status,
            'identifier' => $item->identifier,
        ];
    }
}

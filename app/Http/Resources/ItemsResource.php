<?php

namespace App\Http\Resources;

use App\Models\Item;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Item $item */
        $item = $this->resource;

        return [
            'slug' => $item->slug,
            'name' => $item->name,
            'description' => $item->description,
        ];
    }
}

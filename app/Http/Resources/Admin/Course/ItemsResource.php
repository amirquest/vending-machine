<?php

namespace App\Http\Resources\Admin\Course;

use App\Http\Resources\User\MediaResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Item $this */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'order' => $model->order,
            'type' => $model->type,
            'content' => $model->content,
            'media' => MediaResource::make($model->media?->first()),
            'text' => $model->text,
        ];
    }
}

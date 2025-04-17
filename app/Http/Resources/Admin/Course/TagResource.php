<?php

namespace App\Http\Resources\Admin\Course;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Tag $this */
        $model = $this->resource;

        return [
            'key' => $model->key,
            'name' => $model->name,
        ];
    }
}

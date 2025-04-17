<?php

namespace App\Http\Resources\User\Course;


use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StartPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Page $this */
        $model = $this->resource;

        return [
            'title' => $model->title,
            'course_pages_info' => $model->course_pages_info,
            'has_completed' => $model->has_completed,
            'items' => ItemResource::collection($model->items),
        ];
    }
}

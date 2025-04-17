<?php

namespace App\Http\Resources\User\Course;


use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Page $this */
        $model = $this->resource;

        return [
            'title' => $model->title,
            'course_id' => $model->course_id,
            'next_subsyllabus_id' => $model->next_subsyllabus_id,
            'items' => ItemResource::collection($model->items),
        ];
    }
}

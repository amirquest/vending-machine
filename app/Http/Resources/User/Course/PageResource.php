<?php

namespace App\Http\Resources\User\Course;


use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Page $this */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'order' => $model->order,
            'title' => $model->title,
            'items' => ItemResource::collection($model->items),
            'questions' => QuestionItemResource::collection($model->question_items),
        ];
    }
}

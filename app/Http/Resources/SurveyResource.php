<?php

namespace App\Http\Resources;


use App\Http\Resources\User\Course\ItemResource;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Survey $this */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'title' => $model->title,
            'description' => $model->description,
            'is_active' => $model->is_active,
            'items' => ItemResource::collection($model->items),
            'questions' => SurveyQuestionItemsResource::collection($model->questions),
        ];
    }
}

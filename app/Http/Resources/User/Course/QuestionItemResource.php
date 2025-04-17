<?php

namespace App\Http\Resources\User\Course;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Item $this */
        $item = $this->resource;

        $question = $item->content;

        return [
            'identifier' => $item->identifier,
            'order' => $item->order,
            'description' => $question->description,
            'type' => $question->type,
            'data' => is_null($question->options) ? [] : ['options' => $question->options],
            'is_mutable' => $question->is_mutable,
            'is_multi_select' => $question->is_multi_select,
            'answers' => $question->userAnswer?->answer,
        ];
    }
}

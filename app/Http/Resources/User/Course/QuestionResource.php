<?php

namespace App\Http\Resources\User\Course;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Question $this */
        $question = $this->resource;

        return [
            'identifier' => $question->identifier,
            'description' => $question->description,
            'type' => $question->type,
            'data' => is_null($question->options) ? [] : ['options' => $question->options],
            'is_mutable' => $question->is_mutable,
            'is_multi_select' => $question->is_multi_select,
            'answers' => $question->userAnswer?->answer,
        ];
    }
}

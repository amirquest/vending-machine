<?php

namespace App\Http\Resources\User\Course;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Course $this */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'type' => $model->type,
            'title' => $model->title,
            'description' => $model->description,
            'is_free' => $model->is_free,
            'amount' => $model->amount,
            'requires_invite_code' => $model->requires_invite_code,
            'end_date' => $model->end_date,
            'intro' => ItemResource::collection($model->intro),
            'syllabus' => SyllabusResource::collection($model->syllabuses),
            'has_user_registered' => $model->has_user_registered,
            'has_user_requested' => $model->has_user_requested,
            'progress_percentage' => $model->progress_percentage,
        ];
    }
}

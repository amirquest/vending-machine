<?php

namespace App\Http\Resources\User\Course;

use App\Models\Syllabus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SyllabusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Syllabus $this */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'order' => $model->order,
            'title' => $model->title,
            'status' => $model->status,
            'sub_syllabus' => SubSyllabusResource::collection($model->subSyllabuses)
        ];
    }
}

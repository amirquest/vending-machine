<?php

namespace App\Http\Resources\Admin\Course;

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
            'sub_syllabus' => SubSyllabusResource::collection($model->subSyllabus)
        ];
    }
}

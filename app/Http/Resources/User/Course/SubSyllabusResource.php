<?php

namespace App\Http\Resources\User\Course;

use App\Models\SubSyllabus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubSyllabusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var SubSyllabus $this */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'order' => $model->order,
            'title' => $model->title,
            'status' => $model->status,
        ];
    }
}

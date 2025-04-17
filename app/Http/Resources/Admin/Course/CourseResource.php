<?php

namespace App\Http\Resources\Admin\Course;

use App\Http\Resources\Admin\MediaResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'is_active' => $model->is_active,
            'is_free' => $model->is_free,
            'amount' => $model->amount,
            'requires_invite_code' => $model->requires_invite_code,
            'cover_image' => MediaResource::make($model->media?->first()),
        ];
    }
}

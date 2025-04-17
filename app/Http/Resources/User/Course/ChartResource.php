<?php

namespace App\Http\Resources\User\Course;

use App\Models\Chart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Chart $this */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'title' => $model->title,
            'data' => $model->meta_data,
        ];
    }
}

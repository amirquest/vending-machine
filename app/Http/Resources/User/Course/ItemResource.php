<?php

namespace App\Http\Resources\User\Course;

use App\Enums\ItemTypeEnum;
use App\Http\Resources\User\MediaResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Item $this */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'order' => $model->order,
            'type' => ($model->type === ItemTypeEnum::CHART()) ? $model->content->type : $model->type, // set chart type on this type
            'data' => $this->resolveData($model),
        ];
    }

    private function resolveData($model)
    {
        return match ($model->type) {
          ItemTypeEnum::CHART() => $model->content->meta_data,
          ItemTypeEnum::MEDIA() => MediaResource::make($model->media?->first()),
          ItemTypeEnum::TEXT() =>  $model->text,
          ItemTypeEnum::BOX() =>  json_decode($model->text, true),
          ItemTypeEnum::BUTTON() =>  json_decode($model->text, true),
        };
    }
}

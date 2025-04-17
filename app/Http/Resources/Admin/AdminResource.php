<?php

namespace App\Http\Resources\Admin;

use App\Models\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Admin $model */
        $model = $this->resource;

        return [
            'id' => $model->id,
            'name' => $model->name,
            'family' => $model->family,
            'full_name' => $model->full_name,
            'email' => $model->email,
            'mobile' => $model->mobile,
            'is_active' => $model->is_active,
            'permissions' => $this->whenLoaded(
                'permissions',
                fn() => PermissionResource::collection($model->permissions)
            ),
            'deleted_at' => $model->deleted_at,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources\Admin;

use App\Models\Permission;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Permission $model */
        $model = $this->resource;

        return [
            'id' => $model->id,
            'name' => $model->name,
            'users' => $this->whenLoaded('users', fn() => AdminResource::collection($model->users))
        ];
    }
}

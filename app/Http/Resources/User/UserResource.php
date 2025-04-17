<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $model */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'name' => $model->name,
            'family' => $model->family,
            'full_name' => $model->full_name,
            'email' => $model->email,
            'gender' => $model->gender,
            'mobile' => $model->mobile,
            'birth_date' => $this->birth_date,
            'status' => $model->status,
        ];
    }
}

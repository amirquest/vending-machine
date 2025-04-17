<?php

namespace App\Http\Resources\User;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var UserInfo $model */
        $model = $this->resource;

        return [
            'education_info' => $model->education_info,
            'job_info' => $model->job_info,
            'family_info' => $this->family_info,
            'health_info' => $model->health_info,
            'training_info' => $model->training_info,
            'favourites' => $model->favourites,
        ];
    }
}

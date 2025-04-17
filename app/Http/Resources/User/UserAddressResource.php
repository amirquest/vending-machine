<?php

namespace App\Http\Resources\User;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var UserAddress $this */
        return [
            'id' => $this->id,
            'city_id' => $this->city_id,
            'province_id' => $this->city->province_id,
//            'postal_code' => $this->postal_code,
//            'address' => $this->address,
//            'plaque' => $this->plaque,
//            'unit' => $this->unit,
        ];
    }
}

<?php

namespace App\Http\Resources\Admin\Course;

use App\Models\CourseInviteCode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseInviteCodesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var CourseInviteCode $this */
        return [
          'code' => $this->code,
          'capacity' => $this->capacity,
          'used' => $this->used,
          'has_capacity' => $this->hasCapacity(),
          'course' => CourseResource::make($this->course),
        ];
    }
}

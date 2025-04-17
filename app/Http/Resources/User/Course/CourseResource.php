<?php

namespace App\Http\Resources\User\Course;

use App\Enums\Course\UserCourseStatusEnum;
use App\Http\Resources\User\MediaResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Course $this */
        $model = $this->resource;
        $userCourseStatus = $model->userCourse?->status;

        return [
            'identifier' => $model->identifier,
            'type' => $model->type,
            'title' => $model->title,
            'description' => $model->description,
            'is_free' => $model->is_free,
            'amount' => $model->amount,
            'requires_invite_code' => $model->requires_invite_code,
            'has_completed' => ($userCourseStatus === UserCourseStatusEnum::COMPLETED()),
            'has_user_registered' => in_array($userCourseStatus, [
                UserCourseStatusEnum::REGISTERED(),
                UserCourseStatusEnum::IN_PROGRESS(),
                UserCourseStatusEnum::COMPLETED(),
            ]),
            'progress_percentage' => $model->progress_percentage,
            'is_ongoing' => is_null($model->end_date) || (now() < $model->end_date),
            'end_date' => $model->end_date,
            'cover_image' => MediaResource::make($model->media?->first()),
        ];
    }
}

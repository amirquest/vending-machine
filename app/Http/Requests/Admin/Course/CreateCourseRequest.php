<?php

namespace App\Http\Requests\Admin\Course;

use App\Enums\Course\CourseTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'type' => ['required', 'string', 'in:' . implode(',', CourseTypeEnum::toArray())],
            'description' => ['required', 'string', 'min:3', 'max:1000'],
            'is_active' => ['required', 'boolean'],
            'is_free' => ['required', 'boolean'],
            'amount' => ['required_if:is_free,0'],
            'require_invite_code' => ['required', 'boolean'],
            'cover_image' => ['required', 'file', 'extensions:jpg,jpeg,png,svg', 'max:3096'],
            'start_date' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'end_date' => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }
}

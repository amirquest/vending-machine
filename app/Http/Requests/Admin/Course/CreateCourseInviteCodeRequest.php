<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Foundation\Http\FormRequest;

class CreateCourseInviteCodeRequest extends FormRequest
{
   public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'min:5', 'max:100'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
            'course_identifier' => ['required', 'numeric', 'exists:courses,identifier'],
            'user_id' => ['nullable', 'numeric', 'exists:users,id'],
        ];
    }
}

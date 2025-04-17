<?php

namespace App\Http\Requests\User\Course;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'course_identifier' => ['required', 'numeric', 'exists:courses,identifier'],
            'invite_code' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'min:3', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'is_free' => ['nullable', 'boolean'],
            'amount' => ['required_if:is_free,false'],
            'require_invite_code' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'file', 'extensions:jpg,jpeg,png,svg', 'max:2048'],
        ];
    }
}

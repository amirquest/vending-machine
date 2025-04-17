<?php

namespace App\Http\Requests\Admin\Course\Syllabus;

use App\Enums\Course\CourseTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateSyllabusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order' => ['required', 'integer'],
            'title' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Foundation\Http\FormRequest;

class CreateTagRequest extends FormRequest
{
   public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'min:3', 'max:100'],
            'name' => ['required', 'string', 'min:3', 'max:100'],
        ];
    }
}

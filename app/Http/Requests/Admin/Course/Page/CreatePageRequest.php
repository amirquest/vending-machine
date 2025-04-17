<?php

namespace App\Http\Requests\Admin\Course\Page;

use App\Enums\ItemTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreatePageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            '*' => ['required', 'array'],
            '*.order' => ['nullable', 'numeric'],
            '*.title' => ['required', 'string', 'min:3', 'max:100'],
        ];
    }
}

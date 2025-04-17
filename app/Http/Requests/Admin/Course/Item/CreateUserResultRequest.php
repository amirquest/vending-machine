<?php

namespace App\Http\Requests\Admin\Course\Item;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserResultRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'result' => ['required', 'string', 'min:3', 'max:255'],
            'user_identifier' => ['required', 'numeric', 'exists:users,identifier'],
        ];
    }
}

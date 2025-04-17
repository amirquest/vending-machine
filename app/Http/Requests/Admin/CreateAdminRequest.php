<?php

namespace App\Http\Requests\Admin;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAdminRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'family' => ['required', 'string', 'max:100'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'int'],
            'email' => ['required', 'email', Rule::unique('admins')],
            'mobile' => ['required', MobileRule::make(), Rule::unique('admins')],
            'is_active' => ['boolean'],
            'national_code' => ['required', 'string', 'digits:10', Rule::unique('admins')],
        ];
    }
}

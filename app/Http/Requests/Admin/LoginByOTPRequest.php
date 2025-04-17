<?php

namespace App\Http\Requests\Admin;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginByOTPRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required', new MobileRule()],
            'token' => ['required', 'string', 'size:' . config('otp.token_length')],
            'device_name' => ['required', 'string'],
        ];
    }
}

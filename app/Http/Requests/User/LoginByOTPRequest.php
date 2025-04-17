<?php

namespace App\Http\Requests\User;

use App\Enums\ChannelEnum;
use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginByOTPRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required', new MobileRule()],
            'token' => ['required', 'string', 'size:' . config('otp.token_length')],
            'channel' => ['required', 'in:' . implode(',', ChannelEnum::toArray())],
            'device_name' => ['required', 'string'],
        ];
    }
}

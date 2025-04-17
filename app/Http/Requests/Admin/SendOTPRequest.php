<?php

namespace App\Http\Requests\Admin;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class SendOTPRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required', new MobileRule()],
        ];
    }
}

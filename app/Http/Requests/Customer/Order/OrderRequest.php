<?php

namespace App\Http\Requests\Customer\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'item_id' => ['required', 'integer','exists:items,id'],
            'request_code' => ['required', 'string', 'exists:payments,request_code'],
        ];
    }
}

<?php

namespace App\Http\Requests\Customer\Basket;

use Illuminate\Foundation\Http\FormRequest;

class AddItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.identifier' => ['required', 'integer', 'exists:items,identifier'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}

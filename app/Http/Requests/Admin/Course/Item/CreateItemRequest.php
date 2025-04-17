<?php

namespace App\Http\Requests\Admin\Course\Item;

use App\Enums\ItemTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order' => ['nullable', 'numeric'],
            'type' => ['required', 'string', 'in:' . implode(',', ItemTypeEnum::toArray())],
            'content_id' => [
                'required_if:type,' . implode(',', [ItemTypeEnum::QUESTION(), ItemTypeEnum::CHART()]),
                'integer'
            ],
            'media' => [
                'required_if:type,' . ItemTypeEnum::MEDIA(),
                'file',
                'extensions:jpg,jpeg,png,svg,mp3,wav,ogg,mp4,avi,mov,mkv',
                'max:4096'
            ],
            'text' => [
                'required_if:type,' . ItemTypeEnum::TEXT(),
                'string',
                'min:3',
            ],
        ];
    }
}

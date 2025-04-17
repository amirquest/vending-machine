<?php

namespace App\Http\Requests\User\Course;

use Illuminate\Foundation\Http\FormRequest;

class AnswerPageQuestionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'items' => ['nullable', 'array'],
            'items.*.identifier' => [
                'nullable',
                'integer',
                //todo: why return error?
//                'exists:items,identifier'
            ],
            'items.*.answers' => ['nullable', 'array'],
        ];
    }
}

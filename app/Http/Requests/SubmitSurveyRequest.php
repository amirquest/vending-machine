<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitSurveyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reference' => ['required', 'string'],
            'questions' => ['required', 'array'],
            'questions.*.identifier' => [
                'required',
                'integer',
                //todo: why return error?
//                'exists:items,identifier'
            ],
            'questions.*.answers' => ['required', 'array'],
        ];
    }
}

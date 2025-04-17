<?php

namespace App\Http\Requests\User;

use App\Enums\UserInfo\EducationsEnum;
use App\Enums\UserInfo\FavouritesEnum;
use App\Enums\UserInfo\JobStatusEnum;
use App\Enums\UserInfo\ExcerciseHoursEnum;
use App\Enums\UserInfo\MaritalStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserInfoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'general' => ['array'],
            'general.name' => ['required_with:general', 'string', 'min:3', 'max:100'],
            'general.family' => ['required_with:general', 'string', 'min:3', 'max:100'],
            'general.name_en' => ['nullable', 'string', 'min:3', 'max:100'],
            'general.family_en' => ['nullable', 'string', 'min:3', 'max:100'],
            'general.birth_date' => ['nullable', 'date_format:Y-m-d'],
            'general.email' => [
                'required_with:general',
                'email',
                'max:50',
                Rule::unique('users', 'email')->ignore($this->user()->id)
            ],

            'address' => ['array'],
            'address.city_id' => ['required_with:address', 'exists:cities,id'],

            'favourites' => ['nullable', 'array'],
            'favourites.*' => [
                'required_with:favourites',
                'string',
                'min:3',
                'max:100',
                'in:' . implode(',', FavouritesEnum::toArray())
            ],

            'education_info' => ['nullable', 'array'],
            'education_info.education' => [
                'required_with:education_info',
                'string',
                'min:3',
                'max:100',
                'in:' . implode(',', EducationsEnum::toArray())
            ],
            'education_info.study_field' => ['required_with:education_info', 'string', 'min:3', 'max:100'],


            'job_info' => ['array'],
            'job_info.job_status' => [
                'required_with:job_info_info',
                'string',
                'min:3',
                'max:100',
                'in:' . implode(',', JobStatusEnum::toArray())
            ],
            'job_info.job_field' => ['required_with:job_info_info', 'string', 'min:3', 'max:100'],
            'job_info.organization_name' => ['required_with:job_info', 'string', 'min:3', 'max:100'],


            'family_info' => ['array'],
            'family_info.marital_status' => [
                'required_with:family_info',
                'string',
                'in:' . implode(',', MaritalStatusEnum::toArray())
            ],
            'family_info.daughter_child_count' => ['nullable', 'numeric'],
            'family_info.son_child_count' => ['nullable', 'numeric'],


            'health_info' => ['nullable', 'array'],
            'health_info.weekly_sport_hours' => [
                'required_with:health_info',
                'string',
                'in:' . implode(',', ExcerciseHoursEnum::toArray())
            ],
            'health_info.sports_names' => ['required_with:health_info', 'array'],
            'health_info.sports_names.*' => ['required_with:health_info.sports_names', 'string', 'min:3', 'max:100'],
            'health_info.need_sport_plan' => ['required_with:health_info', 'boolean'],
            'health_info.need_sport_coach' => ['required_with:health_info', 'boolean'],
            'health_info.need_sport_companion' => ['required_with:health_info', 'boolean'],
            'health_info.has_meal_plan' => ['required_with:health_info', 'string', 'in:true,false,CALORIE_COUNTING'],


            'training_info' => ['nullable', 'array'],
            'training_info.weekly_study_hours' => [
                'required_with:training_info',
                'string',
                'in:' . implode(',', ExcerciseHoursEnum::toArray())
            ],
            'training_info.study_fields' => ['required_with:training_info', 'array'],
            'training_info.study_fields.*' => ['required_with:training_info.study_fields', 'string', 'min:3', 'max:100'],
            'training_info.need_study_plan' => ['required_with:training_info', 'boolean'],
            'training_info.need_study_coach' => ['required_with:training_info', 'boolean'],
            'training_info.need_study_companion' => ['required_with:training_info', 'boolean'],

            'training_info.weekly_podcast_hours' => [
                'required_with:training_info',
                'string',
                'in:' . implode(',', ExcerciseHoursEnum::toArray())
            ],
            'training_info.podcast_fields' => ['required_with:training_info', 'array'],
            'training_info.podcast_fields.*' => ['required_with:training_info.podcast_fields', 'string', 'min:3', 'max:100'],
            'training_info.need_podcast_plan' => ['required_with:training_info', 'boolean'],
            'training_info.need_podcast_coach' => ['required_with:training_info', 'boolean'],
            'training_info.need_podcast_companion' => ['required_with:training_info', 'boolean'],

            'training_info.currently_participate_personal_growth_courses' => ['required_with:training_info', 'boolean'],
            'training_info.currently_use_counseling' => ['required_with:training_info', 'boolean'],
            'training_info.use_counseling_details' => [
                'required_if:training_info.use_counseling,true',
                'nullable',
                'string',
                'min:10',
                'max:256'
            ],
            'training_info.need_our_counseling_service' => ['required_with:training_info', 'boolean'],
            'training_info.need_our_counseling_service_details' => [
                'nullable',
                'required_if:training_info.need_our_counseling_service,true',
                'string',
                'min:10',
                'max:256'
            ],

        ];
    }
}

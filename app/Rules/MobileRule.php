<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MobileRule implements ValidationRule
{
    public const string REGEX = '/(0)9[0-9]\d{8}/';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!(bool)preg_match(self::REGEX, $value)) {
            $fail(__('validation.custom.mobile'));
        }
    }

    public static function make(): static
    {
        return new static();
    }
}

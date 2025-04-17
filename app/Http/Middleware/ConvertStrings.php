<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class ConvertStrings extends TransformsRequest
{
    protected function transform($key, $value)
    {
        return is_string($value) ? $this->convert($value) : $value;
    }

    protected function convert($string): string
    {
        $persianAndArabicNumbers = [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
        ];

        return str_replace(
            array_keys($persianAndArabicNumbers),
            array_values($persianAndArabicNumbers),
            $string
        );
    }
}

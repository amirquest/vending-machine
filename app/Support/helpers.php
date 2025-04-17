<?php


if (!function_exists('convertNumberToPersianChar')) {
    /**
     * @throws Exception
     */
    function convertNumberToPersianChar(int $number): string
    {
        $ones = [
            "",
            "یک",
            "دو",
            "سه",
            "چهار",
            "پنج",
            "شش",
            "هفت",
            "هشت",
            "نه",
            "ده",
            "یازده",
            "دوازده",
            "سیزده",
            "چهارده",
            "پانزده",
            "شانزده",
            "هفده",
            "هجده",
            "نوزده",
        ];

        $tens = [
            "",
            "",
            "بیست",
            "سی",
            "چهل",
            "پنجاه",
            "شصت",
            "هفتاد",
            "هشتاد",
            "نود",
        ];
        $tows = [
            "",
            "صد",
            "دویست",
            "سیصد",
            "چهار صد",
            "پانصد",
            "ششصد",
            "هفتصد",
            "هشت صد",
            "نه صد",
        ];

        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }
        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */
        $res = "";
        if ($Gn) {
            $res .= convertNumberToPersianChar($Gn) . " میلیون و ";
        }
        if ($kn) {
            $res .= (empty($res) ? "" : " ") . convertNumberToPersianChar($kn) . " هزار و";
        }
        if ($Hn) {
            $res .= (empty($res) ? "" : " ") . $tows[$Hn] . " و ";
        }
        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= "";
            }
            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= " و " . $ones[$n];
                }
            }
        }
        if (empty($res)) {
            $res = "صفر";
        }

        return rtrim($res, " و");
    }
}

if (!function_exists('latinNumbersToPersian')) {
    function latinNumbersToPersian(string $string): string
    {
        $numberMapping = [
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
        ];

        return str_replace(
            array_values($numberMapping),
            array_keys($numberMapping),
            $string
        );
    }
}

if (!function_exists('format_mobile')) {
    function format_mobile(?string $mobile): ?string
    {
        if (!$mobile) {
            return $mobile;
        }

        if (str_starts_with($mobile, '+98')) {
            $mobile = str_replace('+98', '0', $mobile);
        }

        if (!str_starts_with($mobile, '0')) {
            $mobile = '0' . $mobile;
        }

        return $mobile;
    }
}

if (!function_exists('query_log')) {
    function query_log(callable $callable, string $connection = 'mysql'): void
    {
        \Illuminate\Support\Facades\DB::connection($connection)->enableQueryLog();

        $callable();

        dump(\Illuminate\Support\Facades\DB::connection($connection)->getQueryLog());

        exit(1);
    }
}

if (!function_exists('enable_query_log')) {
    function enable_query_log(string $connection = 'mysql'): void
    {
        \Illuminate\Support\Facades\DB::connection($connection)->enableQueryLog();
    }
}

if (!function_exists('get_query_log')) {
    function get_query_log(string $connection = 'mysql'): mixed
    {
        dump(\Illuminate\Support\Facades\DB::connection($connection)->getQueryLog());

        exit(1);
    }
}


if (!function_exists('is_not_null')) {
    function is_not_null(mixed $value): string
    {
        return !is_null($value);
    }
}


if (!function_exists('add_country_code_to_mobile')) {
    function add_country_code_to_mobile(string $mobile, ?string $code = '98'): ?string
    {
        if (str_starts_with($mobile, '0')) {
            $mobile = $code . substr($mobile, 1);
        }

        if (!str_starts_with($mobile, $code)) {
            $mobile = $code . $mobile;
        }

        return $mobile;
    }
}


if (!function_exists('make_url')) {
    /**
     * @throws Exception
     */
    function make_url(string $baseUrl, string $path = '', array $queryParams = []): string
    {
        if (filter_var($baseUrl, FILTER_VALIDATE_URL) === false) {
            throw new Exception('BaseUrl is not valid!');
        }

        $fullUrl = trim($baseUrl, '/') . DIRECTORY_SEPARATOR . trim($path, '/');
        $httpQueryParams = http_build_query($queryParams);

        return sprintf('%s?%s', $fullUrl, $httpQueryParams);
    }
}


if (!function_exists('url_query')) {
    function url_query($url, array $params = []): string
    {
        return Str::finish(url($url, $params), Str::contains($url, '?') ? '&' : '?') . Arr::query($params);
    }
}


if (!function_exists('is_not_null')) {
    function is_not_null(mixed $value): string
    {
        return !is_null($value);
    }
}

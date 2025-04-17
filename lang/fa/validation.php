<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute باید پذیرفته شود.',
    'accepted_if' => ':attribute باید پذیرفته شود وقتی :other , :value باشد.',
    'active_url' => ':attribute باید یک آدرس سایت معتبر باشد.',
    'after' => ':attribute باید تاریخی بعد از :date باشد.',
    'after_or_equal' => ':attribute باید تاریخی بعد از یا برابر با :date باشد.',
    'alpha' => ':attribute باید فقط شامل حروف باشد.',
    'alpha_dash' => ':attribute باید فقط شامل حروف، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num' => ':attribute باید فقط شامل حروف و اعداد باشد.',
    'array' => ':attribute باید یک آرایه باشد.',
    'ascii' => ':attribute باید فقط شامل کاراکترهای تک‌بایتی و نمادها باشد',
    'before' => ':attribute باید تاریخی قبل از :date باشد.',
    'before_or_equal' => ':attribute باید تاریخی قبل از یا برابر با :date باشد.',
    'between' => [
        'array' => ':attribute باید بین :min و :max آیتم داشته باشد.',
        'file' => ':attribute باید بین :min و :max کیلوبایت باشد.',
        'numeric' => ':attribute باید بین :min و :max باشد.',
        'string' => ':attribute باید بین :min و :max کاراکتر باشد.',
    ],
    'boolean' => ':attribute باید صحیح یا غلط باشد.',
    'can' => ':attribute شامل مقدار غیرمجاز است.',
    'confirmed' => 'تاییدیه :attribute مطابقت ندارد.',
    'contains' => ':attribute یک مقدار ضروری را ندارد.',
    'current_password' => 'رمز عبور اشتباه است.',
    'date' => ':attribute باید یک تاریخ معتبر باشد.',
    'date_equals' => ':attribute باید تاریخی برابر با :date باشد.',
    'date_format' => ':attribute باید با قالب :format مطابقت داشته باشد.',
    'decimal' => ':attribute باید :decimal رقم اعشار داشته باشد.',
    'declined' => ':attribute باید رد شود.',
    'declined_if' => ':attribute باید رد شود وقتی :other :value باشد.',
    'different' => ':attribute و :other باید متفاوت باشند.',
    'digits' => ':attribute باید :digits رقم باشد.',
    'digits_between' => ':attribute باید بین :min و :max رقم باشد.',
    'dimensions' => ':attribute دارای ابعاد نامعتبر تصویر است.',
    'distinct' => ':attribute دارای مقدار تکراری است.',
    'doesnt_end_with' => ':attribute نباید با یکی از موارد زیر پایان یابد: :values.',
    'doesnt_start_with' => ':attribute نباید با یکی از موارد زیر شروع شود: :values.',
    'email' => ':attribute باید یک آدرس ایمیل معتبر باشد',
    'ends_with' => ':attribute باید با یکی از موارد زیر پایان یابد: :values.',
    'enum' => ':attribute انتخاب شده نامعتبر است.',
    'exists' => ':attribute انتخاب شده نامعتبر است.',
    'extensions' => ':attribute باید یکی از پسوندهای زیر را داشته باشد: :values.',
    'file' => ':attribute باید یک فایل باشد.',
    'filled' => ':attribute باید یک مقدار داشته باشد.',
    'gt' => [
        'array' => ':attribute باید بیش از :value آیتم داشته باشد.',
        'file' => ':attribute باید بیشتر از :value کیلوبایت باشد.',
        'numeric' => ':attribute باید بیشتر از :value باشد.',
        'string' => ':attribute باید بیشتر از :value کاراکتر باشد.',
    ],
    'gte' => [
        'array' => ':attribute باید :value آیتم یا بیشتر داشته باشد.',
        'file' => ':attribute باید بیشتر یا برابر با :value کیلوبایت باشد.',
        'numeric' => ':attribute باید بیشتر یا برابر با :value باشد.',
        'string' => ':attribute باید بیشتر یا برابر با :value کاراکتر باشد.',
    ],
    'hex_color' => ':attribute باید یک رنگ هگزا معتبر باشد.',
    'image' => ':attribute باید یک تصویر باشد.',
    'in' => ':attribute انتخاب شده نامعتبر است.',
    'in_array' => ':attribute باید در :other موجود باشد.',
    'integer' => ':attribute باید یک عدد صحیح باشد.',
    'ip' => ':attribute باید یک آدرس IP معتبر باشد.',
    'ipv4' => ':attribute باید یک آدرس IPv4 معتبر باشد.',
    'ipv6' => ':attribute باید یک آدرس IPv6 معتبر باشد.',
    'json' => ':attribute باید یک رشته JSON معتبر باشد.',
    'list' => ':attribute باید یک لیست باشد.',
    'lowercase' => ':attribute باید حروف کوچک باشد.',
    'lt' => [
        'array' => ':attribute باید کمتر از :value آیتم داشته باشد.',
        'file' => ':attribute باید کمتر از :value کیلوبایت باشد.',
        'numeric' => ':attribute باید کمتر از :value باشد.',
        'string' => ':attribute باید کمتر از :value کاراکتر باشد.',
    ],
    'lte' => [
        'array' => ':attribute نباید بیشتر از :value آیتم داشته باشد.',
        'file' => ':attribute باید کمتر یا برابر با :value کیلوبایت باشد.',
        'numeric' => ':attribute باید کمتر یا برابر با :value باشد.',
        'string' => ':attribute باید کمتر یا برابر با :value کاراکتر باشد.',
    ],
    'mac_address' => ':attribute باید یک آدرس MAC معتبر باشد.',
    'max' => [
        'array' => ':attribute نباید بیشتر از :max آیتم داشته باشد.',
        'file' => ':attribute نباید بیشتر از :max کیلوبایت باشد.',
        'numeric' => ':attribute نباید بیشتر از :max باشد.',
        'string' => ':attribute نباید بیشتر از :max کاراکتر باشد.',
    ],
    'max_digits' => ':attribute نباید بیشتر از :max رقم باشد.',
    'mimes' => ':attribute باید یک فایل از نوع: :values باشد.',
    'mimetypes' => ':attribute باید یک فایل از نوع: :values باشد.',
    'min' => [
        'array' => ':attribute باید حداقل :min آیتم داشته باشد.',
        'file' => ':attribute باید حداقل :min کیلوبایت باشد.',
        'numeric' => ':attribute باید حداقل :min باشد.',
        'string' => ':attribute باید حداقل :min کاراکتر باشد.',
    ],
    'min_digits' => ':attribute باید حداقل :min رقم باشد.',
    'missing' => ':attribute نباید وجود داشته باشد.',
    'missing_if' => ':attribute نباید وجود داشته باشد وقتی :other :value باشد.',
    'missing_unless' => ':attribute نباید وجود داشته باشد مگر اینکه :other :value باشد.',
    'missing_with' => ':attribute نباید وجود داشته باشد وقتی :values وجود دارد',
    'missing_with_all' => ':attribute نباید وجود داشته باشد وقتی :values وجود دارند.',
    'multiple_of' => ':attribute باید مضربی از :value باشد.',
    'not_in' => ':attribute انتخاب شده نامعتبر است.',
    'not_regex' => 'فرمت :attribute نامعتبر است.',
    'numeric' => ':attribute باید یک عدد باشد.',
    'password' => [
        'letters' => ':attribute باید حداقل یک حرف داشته باشد.',
        'mixed' => ':attribute باید حداقل یک حرف بزرگ و یک حرف کوچک داشته باشد.',
        'numbers' => ':attribute باید حداقل یک عدد داشته باشد.',
        'symbols' => ':attribute باید حداقل یک نماد داشته باشد.',
        'uncompromised' => ':attribute وارد شده در یک نفوذ داده مشاهده شده است. لطفاً یک :attribute دیگر انتخاب کنید.',
    ],
    'present' => ':attribute باید موجود باشد.',
    'present_if' => ':attribute باید موجود باشد وقتی :other :value باشد.',
    'present_unless' => ':attribute باید موجود باشد مگر اینکه :other :value باشد.',
    'present_with' => ':attribute باید موجود باشد وقتی :values وجود دارد.',
    'present_with_all' => ':attribute باید موجود باشد وقتی :values وجود دارند.',
    'prohibited' => ':attribute مجاز نیست.',
    'prohibited_if' => ':attribute مجاز نیست وقتی :other :value باشد.',
    'prohibited_unless' => ':attribute مجاز نیست مگر اینکه :other در :values باشد.',
    'prohibits' => ':attribute از حضور :other جلوگیری می‌کند.',
    'regex' => 'فرمت :attribute نامعتبر است',
    'required' => ':attribute الزامی است.',
    'required_array_keys' => ':attribute باید شامل مقادیر زیر باشد: :values.',
    'required_if' => ':attribute الزامی است وقتی :other :value باشد.',
    'required_if_accepted' => ':attribute الزامی است وقتی :other پذیرفته شده باشد.',
    'required_if_declined' => ':attribute الزامی است وقتی :other رد شده باشد.',
    'required_unless' => ':attribute الزامی است مگر اینکه :other در :values باشد.',
    'required_with' => ':attribute الزامی است وقتی :values موجود است.',
    'required_with_all' => ':attribute الزامی است وقتی :values موجود هستند.',
    'required_without' => ':attribute الزامی است وقتی :values موجود نیست.',
    'required_without_all' => ':attribute الزامی است وقتی هیچکدام از :values موجود نیستند.',
    'same' => ':attribute باید با :other مطابقت داشته باشد.',
    'size' => [
        'array' => ':attribute باید شامل :size آیتم باشد.',
        'file' => ':attribute باید :size کیلوبایت باشد.',
        'numeric' => ':attribute باید :size باشد.',
        'string' => ':attribute باید :size کاراکتر باشد.',
    ],
    'starts_with' => ':attribute باید با یکی از موارد زیر شروع شود: :values.',
    'string' => ':attribute باید یک رشته باشد.',
    'timezone' => ':attribute باید یک منطقه زمانی معتبر باشد.',
    'unique' => ':attribute قبلاً استفاده شده است',
    'uploaded' => 'آپلود :attribute ناموفق بود.',
    'uppercase' => ':attribute باید با حروف بزرگ باشد',
    'url' => ':attribute باید یک آدرس وب معتبر باشد.',
    'ulid' => ':attribute باید یک ULID معتبر باشد.',
    'uuid' => ':attribute باید یک UUID معتبر باشد.',
    'mobile' => ':attribute باید دارای فرمت معتبر باشد.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'national_code' => 'کد ملی',
        'mobile' => 'شماره موبایل',
        'token' => 'توکن',
        'channel' => 'چنل',
        'device_name' => 'نام دستگاه',

        'general' => 'اطلاعات کلی',
        'general.email' => 'ایمیل',
        'general.name' => 'نام',
        'general.family' => 'نام خانوادگی',
        'general.name_en' => 'نام لاتین',
        'general.family_en' => 'نام خانوادگی لاتین',
        'general.birth_date' => 'تاریخ تولد',

        'address' => 'اطلاعات محل سکونت',
        'address.city_id' => 'استان/شهر',
    ],

];

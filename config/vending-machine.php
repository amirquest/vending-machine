<?php

return [
    'base_url' => env('VENDOR_MACHINE_BASE_URL', 'https://vm-address.com'),

    'preparation_time' => 1, //Min
    'default_healthy_retry_interval' => 2, //Min

    'penalty_coefficient' => 2,
];

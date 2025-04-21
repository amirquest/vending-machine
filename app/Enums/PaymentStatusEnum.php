<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

/**
 * @method static RETURNED()
 * @method static PAID()
 */
enum PaymentStatusEnum
{
    use BaseEnum;

    case RETURNED;
    case PAID;
}

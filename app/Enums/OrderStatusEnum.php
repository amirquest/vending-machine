<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

/**
 * @method static INIT()
 * @method static SUBMITTED()
 * @method static DELIVERED()
 * @method static CHOOSING_VENDING_MACHINE()
 * @method static FAILED()
 */
enum OrderStatusEnum
{
    use BaseEnum;

    case INIT;
    case SUBMITTED;
    case DELIVERED;
    case CHOOSING_VENDING_MACHINE;
    case FAILED;
}

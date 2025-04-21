<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

/**
 * @method static IDLE()
 * @method static PREPARING()
 * @method static TEST()
 * @method static UNHEALTHY()
 */
enum VendingMachineStatusEnum
{
    use BaseEnum;

    case IDLE;
    case PREPARING;
    case TEST;
    case UNHEALTHY;
}

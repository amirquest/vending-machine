<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

/**
 * @method static IDLE()
 * @method static PREPARING()
 * @method static TEST()
 * @method static OUT_OF_SERVICE()
 */
enum VendorMachineStatusEnum
{
    use BaseEnum;

    case IDLE;
    case PREPARING;
    case TEST;
    case OUT_OF_SERVICE;
}

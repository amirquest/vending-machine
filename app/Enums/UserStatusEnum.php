<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

/**
 * @method static NORMAL()
 * @method static BLOCKED()
 */
enum UserStatusEnum
{
    use BaseEnum;

    case NORMAL;
    case BLOCKED;
}

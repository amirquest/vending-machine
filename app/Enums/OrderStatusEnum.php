<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

/**
 * @method static ESTABLISHED()
 * @method static CANCELED()
 * @method static REJECTED()
 * @method static FINISHED()
 */
enum OrderStatusEnum
{
    use BaseEnum;

    case IDLE;
    case CHOOSING_PRODUCT;
    case PREPARING_PRODUCT;
    case DELIVERED;
}

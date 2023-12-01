<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CANCELED()
 * @method static static PENDING()
 * @method static static ACTIVE()
 */
final class SubscriptionStatus extends Enum
{
    public const CANCELED = 0;

    public const PENDING = 1;

    public const ACTIVE = 2;
}

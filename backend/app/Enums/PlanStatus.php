<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static INACTIVE()
 * @method static static ACTIVE()
 */
final class PlanStatus extends Enum
{
    public const INACTIVE = 0;

    public const ACTIVE = 1;
}
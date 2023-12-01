<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static WEEK()
 * @method static static MONTH()
 * @method static static YEAR()
 */
final class PlanBillCycle extends Enum
{
    public const WEEK = 'week';

    public const MONTH = 'month';

    public const YEAR = 'year';
}

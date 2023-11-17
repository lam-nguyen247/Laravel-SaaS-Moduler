<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ADMIN()
 * @method static static SUPER_ADMIN()
 * @method static static USER()
 */
final class UserRole extends Enum
{
    public const ADMIN = 'admin';

    public const SUPER_ADMIN = 'super_admin';

    public const USER = 'user';
}

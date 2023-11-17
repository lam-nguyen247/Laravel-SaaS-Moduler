<?php

namespace App\Modules\User\Repositories;

use App\Models\PasswordReset;
use App\Repositories\AbstractRepository;

class PasswordResetRepository extends AbstractRepository
{
    public function __construct(PasswordReset $passwordReset)
    {
        $this->model = $passwordReset;

        parent::__construct();
    }
}

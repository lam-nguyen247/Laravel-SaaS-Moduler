<?php

namespace App\Modules\User\Services;

use App\Modules\User\Repositories\PasswordResetRepository;
use App\Services\AbstractService;

class PasswordResetService extends AbstractService
{
    public function __construct(PasswordResetRepository $passwordResetRepository)
    {
        $this->repository = $passwordResetRepository;
    }
}

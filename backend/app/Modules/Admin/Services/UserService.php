<?php

namespace App\Modules\Admin\Services;

use App\Services\AbstractService;
use App\Modules\Admin\Repositories\UserRepository;

class UserService extends AbstractService
{
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }
}

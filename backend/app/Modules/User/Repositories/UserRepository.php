<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use App\Repositories\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;

        parent::__construct();
    }
}

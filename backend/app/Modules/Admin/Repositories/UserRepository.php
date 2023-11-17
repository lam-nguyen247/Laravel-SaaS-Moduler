<?php

namespace App\Modules\Admin\Repositories;

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

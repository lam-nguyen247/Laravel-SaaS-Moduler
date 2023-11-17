<?php

namespace App\Modules\Admin\Repositories;

use App\Models\Admin;
use App\Repositories\AbstractRepository;

class AdminRepository extends AbstractRepository
{
    public function __construct(Admin $admin)
    {
        $this->model = $admin;

        parent::__construct();
    }
}

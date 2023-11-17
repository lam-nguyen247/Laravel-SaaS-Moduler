<?php

namespace App\Modules\SuperAdmin\Repositories;

use App\Models\SuperAdmin;
use App\Repositories\AbstractRepository;

class SuperAdminRepository extends AbstractRepository
{
    public function __construct(SuperAdmin $superAdmin)
    {
        $this->model = $superAdmin;

        parent::__construct();
    }
}

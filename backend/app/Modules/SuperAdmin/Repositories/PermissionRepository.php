<?php

namespace App\Modules\SuperAdmin\Repositories;

use App\Repositories\AbstractRepository;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends AbstractRepository
{
    public function __construct(Permission $permission)
    {
        $this->model = $permission;

        parent::__construct();
    }
}

<?php

namespace App\Modules\SuperAdmin\Repositories;

use App\Repositories\AbstractRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends AbstractRepository
{
    public function __construct(Role $role)
    {
        $this->model = $role;

        parent::__construct();
    }
}

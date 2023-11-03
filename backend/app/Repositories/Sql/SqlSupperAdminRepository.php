<?php

namespace App\Repositories\Sql;

use App\Models\SupperAdmin;
use App\Repositories\AbstractRepository;

class SqlSupperAdminRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->model = new SupperAdmin();

        parent::__construct();
    }
}

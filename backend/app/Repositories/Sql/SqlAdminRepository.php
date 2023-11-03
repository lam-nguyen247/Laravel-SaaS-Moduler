<?php

namespace App\Repositories\Sql;

use App\Models\Admin;
use App\Repositories\AbstractRepository;

class SqlAdminRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->model = new Admin();

        parent::__construct();
    }
}

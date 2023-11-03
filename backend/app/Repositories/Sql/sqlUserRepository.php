<?php

namespace App\Repositories\Sql;

use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class sqlUserRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->model = new User();

        parent::__construct();
    }

    public function getPaginate(int $perPage): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }
}

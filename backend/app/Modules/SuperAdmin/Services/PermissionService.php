<?php

namespace App\Modules\SuperAdmin\Services;

use App\Modules\SuperAdmin\Repositories\PermissionRepository;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Collection;

class PermissionService extends AbstractService
{
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->repository = $permissionRepository;
    }

    /**
     * Get all records
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->repository->getWithConditions();
    }
}

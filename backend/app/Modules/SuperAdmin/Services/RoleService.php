<?php

namespace App\Modules\SuperAdmin\Services;

use App\Modules\SuperAdmin\Repositories\RoleRepository;
use App\Services\AbstractService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService extends AbstractService
{
    public function __construct(RoleRepository $roleRepository)
    {
        $this->repository = $roleRepository;
    }

    /**
     * Get all records
     * @param string|array $relations
     *
     * @return Collection|array
     */
    public function all(string|array $relations = null): Collection|array
    {
        return $this->repository->getWithConditions('', $relations);
    }

    /**
     * Get role by id and relation
     *
     * @param  string    $id
     * @param  array     $relations
     * @return Role|null
     */
    public function findOne(string $id, array $relations = []): ?Role
    {
        return $this->repository->findOne($id, $relations);
    }

    /**
     * Create role
     *
     * @param array $input
     *
     * @return Role
     * @throws Exception
     */
    public function createRole(array $input): Role
    {
        try {
            DB::beginTransaction();
            $role = $this->repository->create($input);
            //@phpstan-ignore-next-line
            $role->givePermissionTo($input['permissions']);
            //@phpstan-ignore-next-line
            $role['permissions'] = $role->getPermissionNames();
            DB::commit();

            return $role;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception();
        }
    }

    /**
     * Update permissions of role
     *
     * @param Role  $role
     * @param array $permissions
     * @param bool  $isAddPermission
     *
     * @return Role
     */
    public function updatePermissions(Role $role, array $permissions, bool $isAddPermission = false): Role
    {
        if ($isAddPermission) {
            return $role->givePermissionTo($permissions);
        }

        return $role->revokePermissionTo($permissions);
    }

    /**
     * Check to see if all permissions have been removed
     *
     * @param Role  $role
     * @param array $permissions
     *
     * @return bool
     */
    public function checkRemoveAllPermissions(Role $role, array $permissions): bool
    {
        if (count($role->permissions) === count($permissions)) {
            return true;
        }

        return false;
    }
}

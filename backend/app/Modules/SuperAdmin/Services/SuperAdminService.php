<?php

namespace App\Modules\SuperAdmin\Services;

use App\Models\SuperAdmin;
use App\Modules\SuperAdmin\Repositories\SuperAdminRepository;
use App\Modules\SuperAdmin\Repositories\AdminRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class SuperAdminService
{
    private SuperAdminRepository $superAdminRepository;

    private AdminRepository $adminRepository;

    /**
     * @param SuperAdminRepository $superAdminRepository
     * @param AdminRepository      $adminRepository
     */
    public function __construct(SuperAdminRepository $superAdminRepository, AdminRepository $adminRepository)
    {
        $this->superAdminRepository = $superAdminRepository;
        $this->adminRepository = $adminRepository;
    }

    /**
     * Find super admin
     *
     * @param int   $id
     * @param array $relations
     */
    public function findOne(int $id, array $relations = null)
    {
        return $this->superAdminRepository->findOne($id, $relations);
    }

    /**
     * Compare password with hasPassword.
     *
     * @param string $password
     * @param string $hasPassword
     *
     * @return bool
     */
    public function isComparePassword($password = null, $hasPassword = null): bool
    {
        return Hash::check($password, $hasPassword);
    }

    /**
     * Change password
     *
     * @param SuperAdmin $user
     * @param string     $newPassword
     *
     * @return bool
     */
    public function changePassword(SuperAdmin $user, string $newPassword): bool
    {
        return $user->fill([
            'password' => Hash::make($newPassword),
        ])->save();
    }

    /**
     * Update super admin
     *
     * @param SuperAdmin $user
     * @param array      $input
     *
     * @return Model
     */
    public function update(SuperAdmin $user, array $input): Model
    {
        $dataUpdate = [
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'number_phone' => $input['number_phone'],
            'address' => $input['address'],
        ];

        return $this->superAdminRepository->update($user, $dataUpdate);
    }

    /**
     * Find admin
     *
     * @param int   $id
     * @param array $relations
     */
    public function findAdmin(int $id, array $relations = null)
    {
        return $this->adminRepository->findOne($id, $relations);
    }
}

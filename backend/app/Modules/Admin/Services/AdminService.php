<?php

namespace App\Modules\Admin\Services;

use App\Enums\UserRole;
use App\Models\Admin;
use App\Modules\Admin\Repositories\AdminRepository;
use App\Services\AbstractService;
use Illuminate\Support\Facades\Hash;

class AdminService extends AbstractService
{
    public function __construct(AdminRepository $adminRepository)
    {
        $this->repository = $adminRepository;
    }

    /**
     * Get token admin when login
     *
     * @param array $data
     *
     * @return ?string
     */
    public function verifyLogin(array $data): ?string
    {
        //@phpstan-ignore-next-line
        return auth('admin-api')->claims([
            'role' => UserRole::ADMIN,
            'email' => $data['email'],
        ])->attempt($data);
    }

    /**
     * @param Admin $admin
     * @param array $data
     *
     * @return bool
     */
    public function changePassword(Admin $admin, array $data): bool
    {
        if (!Hash::check($data['old_password'], $admin?->password)) {
            return false;
        }

        $response = $this->update($admin, [
            'password' => $data['new_password'],
        ]);

        return $response instanceof Admin;
    }
}

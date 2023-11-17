<?php

namespace App\Modules\SuperAdmin\Services;

use App\Modules\SuperAdmin\Models\Admin;
use App\Modules\SuperAdmin\Repositories\AdminRepository;
use Illuminate\Database\Eloquent\Collection;

class AdminService
{
    private AdminRepository $adminRepository;

    public const AUTH_CODE = 'super-admin';

    public const ACTIVE = 'active';

    public const IN_ACTIVE = 'inactive';

    /**
     * Constructor
     * @param AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * Find admin by email
     * @param  string $email
     * @return Admin
     */
    public function findByEmail(string $email): Admin
    {
        return $this->adminRepository->findUserByEmail($email);
    }

    /**
     * store a admin
     * @param  array $data
     * @return array
     */
    public function storeUser(array $data): array
    {
        $data['updated_by'] = auth($this::AUTH_CODE)->id();
        $data['created_by'] = auth($this::AUTH_CODE)->id();

        return [$this->adminRepository->storeAdmin($data)];
    }

    /**
     * list admins
     * @return Collection
     */
    public function getListAdmin(): Collection
    {
        return $this->adminRepository->listAdmin();
    }

    /**
     * Block admin
     *
     * @param  int  $id
     * @return bool
     */
    public function block(int $id): bool
    {
        /** @var Admin $admin */
        $admin = $this->adminRepository->findById($id);

        if (!$admin) {
            return false;
        }

        $this->adminRepository->update($admin, ['status' => $this::IN_ACTIVE]);

        return true;
    }

    /**
     * Unblock admin
     *
     * @param  int  $id
     * @return bool
     */
    public function unblock(int $id): bool
    {
        /** @var Admin $admin */
        $admin = $this->adminRepository->findById($id);

        if (!$admin) {
            return false;
        }

        $this->adminRepository->update($admin, ['status' => $this::ACTIVE]);

        return true;
    }

    /**
     * Find admin by id
     * @param  int   $id
     * @return Admin
     */
    public function findById(int $id)
    {
        return $this->adminRepository->findById($id);
    }
}

<?php

namespace App\Modules\SuperAdmin\Repositories;

use App\Modules\SuperAdmin\Models\Admin;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Collection;

class AdminRepository extends AbstractRepository
{
    public function __construct(Admin $admin)
    {
        $this->model = $admin;

        parent::__construct();
    }

    /**
     * get Admin by email
     *
     * @param  string $email
     * @return Admin
     */
    public function findUserByEmail(string $email): ?Admin
    {
        return $this->model->query()->where('email', $email)->first();
    }

    /**
     * create Admin
     *
     * @param  array $data
     * @return Admin
     */
    public function storeAdmin(array $data): ?Admin
    {
        return $this->model->create($data);
    }

    /**
     * list Admin
     * @return Collection
     */
    public function listAdmin(): Collection
    {
        return $this->model->get();
    }

    /**
     * Find by id
     *
     * @param  int    $id
     * @return ?Admin
     */
    public function findById(int $id): ?Admin
    {
        return $this->model->query()->find($id);
    }

    /**
     * delete by id
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteAdmin($id): bool
    {
        return Admin::where('id', $id)->delete();
    }
}

<?php

namespace App\Services;

use App\Repositories\Sql\SqlSupperAdminRepository;
use Illuminate\Support\Facades\Hash;

class SupperAdminService
{
    private SqlSupperAdminRepository $sqlSupperAdminRepository;

    /**
     * @param SqlSupperAdminRepository $sqlSupperAdminRepository
     */
    public function __construct(SqlSupperAdminRepository $sqlSupperAdminRepository)
    {
        $this->sqlSupperAdminRepository = $sqlSupperAdminRepository;
    }

    /**
     * Compare password with hasPassword.
     * 
     * @param string $password
     * @param string $hasPassword
     * 
     * @return Boolean
     */
    public function isComparePassword($password, $hasPassword): bool
    {
        return Hash::check($password, $hasPassword);
    }
}

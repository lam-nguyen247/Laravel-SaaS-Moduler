<?php

namespace App\Services;

use App\Repositories\Sql\SqlAdminRepository;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    private SqlAdminRepository $sqlAdminRepository;

    /**
     * @param SqlAdminRepository $sqlAdminRepository
     */
    public function __construct(SqlAdminRepository $sqlAdminRepository)
    {
        $this->sqlAdminRepository = $sqlAdminRepository;
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

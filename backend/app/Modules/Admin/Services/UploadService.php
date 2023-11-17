<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Repositories\UploadRepository;
use App\Services\AbstractService;

class UploadService extends AbstractService
{
    public function __construct(UploadRepository $uploadRepository)
    {
        $this->repository = $uploadRepository;
    }
}

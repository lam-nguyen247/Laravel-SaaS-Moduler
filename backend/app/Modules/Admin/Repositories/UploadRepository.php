<?php

namespace App\Modules\Admin\Repositories;

use App\Modules\Admin\Models\Upload;
use App\Repositories\AbstractRepository;

class UploadRepository extends AbstractRepository
{
    public function __construct(Upload $upload)
    {
        $this->model = $upload;

        parent::__construct();
    }
}

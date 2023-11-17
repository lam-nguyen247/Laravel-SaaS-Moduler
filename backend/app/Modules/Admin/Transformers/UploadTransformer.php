<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\Admin\Models\Upload;
use App\Transformers\AbstractTransformer;

class UploadTransformer extends AbstractTransformer
{
    public function transform(Upload $upload): array
    {
        return [
            'id' => $upload->id,
            'code' => $upload->code,
            'type' => $upload->type,
            'category' => $upload->category,
            'label' => $upload->label,
            'value' => $upload->value,
            'editable' => $upload->editable,
            'is_public' => $upload->is_public,
            'created_by' => $upload->created_by,
            'updated_by' => $upload->updated_by,
            'created_at' => $upload->created_at,
            'updated_at' => $upload->updated_at,
            'deleted_at' => $upload->deleted_at,
        ];
    }
}

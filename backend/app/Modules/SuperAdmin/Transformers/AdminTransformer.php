<?php

namespace App\Modules\SuperAdmin\Transformers;

use App\Modules\SuperAdmin\Models\Admin;

class AdminTransformer extends AbstractTransformer
{
    public function transform(Admin $admin): array
    {
        return [
            'id' => $admin->id,
            'first_name' => $admin->first_name,
            'last_name' => $admin->last_name,
            'email' => $admin->email,
            'address' => $admin->address,
            'status' => $admin->status,
            'phone_country_code' => $admin->phone_country_code,
            'number_phone' => $admin->number_phone,
            'token' => $admin->token,
            'remember_token' => $admin->remember_token,
            'properties' => $admin->properties,
            'created_by' => $admin->created_by,
            'updated_by' => $admin->updated_by,
            'created_at' => $admin->created_at,
            'updated_at' => $admin->updated_at,
            'deleted_at' => $admin->deleted_at,
        ];
    }
}

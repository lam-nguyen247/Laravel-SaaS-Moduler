<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer extends AbstractTransformer
{
    public function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'address' => $user->address,
            'job_title' => $user->job_title,
            'status' => $user->status,
            'classification' => $user->classification,
            'phone_country_code' => $user->phone_country_code,
            'number_phone' => $user->number_phone,
            'confirmed_at' => $user->confirmed_at,
            'confirmation_code' => $user->confirmation_code,
            'token' => $user->token,
            'remember_token' => $user->remember_token,
            'properties' => $user->properties,
            'created_by' => $user->created_by,
            'updated_by' => $user->updated_by,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'deleted_at' => $user->deleted_at,
        ];
    }
}

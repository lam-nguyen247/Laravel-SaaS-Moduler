<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max: 255'],
            'last_name' => ['required', 'string', 'max: 255'],
            'email' => ['required', 'string', 'max: 255', 'email', 'unique:users,email'],
            'password' => ['required'],
            'address' => ['required', 'string', 'max: 255'],
            'job_title' => ['nullable', 'string', 'max: 255'],
            'classification' => ['nullable', 'string', 'max: 255'],
            'phone_country_code' => ['nullable', 'string', 'max: 255'],
            'number_phone' => ['required', 'digits:10', 'unique:users,number_phone'],
            'properties' => ['nullable', 'string', 'max: 255'],
        ];
    }
}

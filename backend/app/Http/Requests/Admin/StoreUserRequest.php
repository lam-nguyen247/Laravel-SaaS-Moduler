<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'first_name' => ['nullable', 'string', 'max: 255'],
            'last_name'=> ['nullable', 'string', 'max: 255'],
            'email'=> ['nullable', 'string', 'max: 255', 'email', 'unique:users,email'],
            'address' => ['nullable', 'string', 'max: 255'],
            'job_title' => ['nullable', 'string', 'max: 255'],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'classification' => ['nullable', 'string', 'max: 255'],
            'phone_country_code' => ['nullable', 'string', 'max: 255'],
            'number_phone' => ['nullable', 'digits:10', 'unique:users,number_phone'],
            'properties' => ['nullable', 'string', 'max: 255'],
        ];
    }
}

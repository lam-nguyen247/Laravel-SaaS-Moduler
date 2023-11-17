<?php

namespace App\Modules\User\Requests;

use DateTimeInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => [
                'nullable',
                'string',
                'max: 255',
            ],
            'last_name' => [
                'nullable',
                'string',
                'max: 255',
            ],
            'address' => [
                'nullable',
                'string',
                'max: 255',
            ],
            'job_title' => [
                'nullable',
                'string',
                'max: 255',
            ],
            'classification' => [
                'nullable',
                'string',
                'max: 255',
            ],
            'properties' => [
                'nullable',
                'string',
                'max: 255',
            ],
            'updated_at' => [
                'required',
                'date_format:' . DateTimeInterface::ATOM,
            ],
        ];
    }
}

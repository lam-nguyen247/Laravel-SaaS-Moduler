<?php

namespace App\Modules\Admin\Requests;

use DateTimeInterface;
use Illuminate\Foundation\Http\FormRequest;

class ChangeProfileRequest extends FormRequest
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
            'name' => [
                'nullable',
                'max: 255',
            ],
            'address' => [
                'nullable',
                'max: 255',
            ],
            'phone' => [
                'nullable',
                'digits:10',
            ],
            'updated_at' => [
                'required',
                'date_format:' . DateTimeInterface::ATOM,
            ],
        ];
    }
}

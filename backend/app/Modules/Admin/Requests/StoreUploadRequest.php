<?php

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUploadRequest extends FormRequest
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
            'code' => ['required', 'string', 'max: 255'],
            'type' => ['required', 'string', 'max: 255'],
            'category' => ['required', 'string', 'max: 255'],
            'label' => ['required', 'string', 'max: 255'],
            'value' => ['required', 'string', 'max: 255'],
            'editable' => ['nullable', 'string', 'max: 255'],
            'is_public' => ['required', Rule::in([0, 1])],
        ];
    }
}

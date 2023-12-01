<?php

namespace App\Modules\SuperAdmin\Requests\AdminManagement;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class EditAdminRequest extends FormRequest
{
    use ResponseTrait;

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
            'id' => 'required|integer|exists:administrators,id',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|email|unique:administrators,email',
            'password' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => ['nullable|string', Rule::in(['active', 'inactive'])],
            'phone_country_code' => 'nullable|string|Max:3',
            'number_phone' => 'nullable|string|Max:11',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validateErrorResponse($validator));
    }

    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }
}

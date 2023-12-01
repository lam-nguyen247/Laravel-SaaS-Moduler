<?php

namespace App\Modules\Subscription\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSubscriptionRequest extends FormRequest
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
            'subscription_reference' => ['required', 'string', 'max: 255'],
            'gateway' => ['required', 'string', 'max: 255'],
            'extras' => ['required', 'string', 'max: 255'],
            'properties' => ['required', 'string', 'max: 255'],
        ];
    }

    public function messages()
    {
        return [
            'subscription_reference.required' => 'The subscription_reference field is required.',
            'gateway.required' => 'The gateway field is required.',
            'extras.required' => 'The extras field is required.',
            'properties.required' => 'The properties field is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422));
    }
}

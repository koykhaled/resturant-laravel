<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

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
            'full_name' => 'string|max:255',
            'email' => 'email|regex:/^[\w\.\-]+@[\w\.\-]+\.[a-zA-Z]{2,6}$/',
            'phone' => 'string|max:20',
            'addresses' => 'nullable|array',
            'addresses.*.type' => 'required|exists:addresses,id',
            'addresses.*.city' => 'required|string|max:255',
            'addresses.*.country' => 'required|string|max:255',
            'addresses.*.street' => 'required|string|max:255',
            'addresses.*.state' => 'required|string|max:255',
            'addresses.*.post_code' => 'required|numeric',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Updated Failed',
            'errors' => $errors
        ], 400));
    }
}
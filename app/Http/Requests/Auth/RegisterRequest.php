<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->password_confirmation) {
                if ($this->password !== $this->password_confirmation) {
                    $validator->errors()->add('password_mismatch', 'The passwords do not match.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'full_name.required' => 'The full name is required.',
            'email.required' => 'We need your email address to register.',
            'email.email' => 'The email address must be valid.',
            'phone.required' => 'Please provide a phone number.',
            'password.required' => 'A password is required to register.',
            'password_confirmation' => 'Please confirme your password.',
            'password_mismatch' => 'The passwords do not match.',
            'phone.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email is already registered.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Register Failed',
            'errors' => $errors
        ], 400));
    }
}
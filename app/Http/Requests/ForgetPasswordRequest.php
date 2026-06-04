<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
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
            'email' => ['sometimes', 'nullable', 'email', 'exists:users,email'],
            'mobile' => ['sometimes', 'nullable', 'string', 'exists:users,mobile'],
        ];
    }
    public function withValidator($validator)
    {
       $validator->after(function ($validator) {
        if (!$this->filled('email') && !$this->filled('mobile')) {
            $validator->errors()->add('email', 'You must provide either email or phone number.');
        }
    });
    }
}

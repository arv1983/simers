<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:users,cpf',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'birthday' => 'required|date',
            'phone' => ['required', 'regex:/^\d{11}$/'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name may not be greater than 255 characters.',

            'cpf.required' => 'The CPF is required.',
            'cpf.size' => 'The CPF must be exactly 11 digits.',
            'cpf.unique' => 'This CPF is already registered.',

            'email.required' => 'The email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',

            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 6 characters.',

            'birthday.required' => 'The birthday is required.',
            'birthday.date' => 'Please provide a valid date for birthday.',

            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number must be exactly 11 digits.',
        ];
    }
}

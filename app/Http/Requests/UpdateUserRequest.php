<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$userId",
            'birthday' => 'required|date',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
        ];
    }
}
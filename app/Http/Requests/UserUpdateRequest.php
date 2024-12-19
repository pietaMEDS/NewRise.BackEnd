<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust as necessary for your authorization logic
    }

    public function rules()
    {
        return [
            'email' => 'sometimes|required|email|unique:users,email',
            'name' => 'sometimes|required|string|unique:users,name',
            'avatar' => 'sometimes|nullable|string',
        ];
    }
}

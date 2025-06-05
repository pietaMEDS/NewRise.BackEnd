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
            'email' => 'sometimes|required|email',
            'name' => 'sometimes|required|string',
            'avatar' => 'sometimes|nullable|string',
            'progress' => 'sometimes|nullable',
            'role' => 'sometimes|required',
        ];
    }
}

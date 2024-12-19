<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessagesCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Настройте по мере необходимости для вашей логики авторизации
    }

    public function rules()
    {
        return [
            'text' => 'string',
            'forum_id' => 'exists:forums,id',
            'message_id' => 'nullable|exists:messages,id',
        ];
    }
}

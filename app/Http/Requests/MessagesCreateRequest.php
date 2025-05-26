<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessagesCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'text' => 'string|max:2000|required',
            'forum_id' => 'exists:forums,id|required',
            'message_id' => 'nullable|exists:messages,id',
        ];
    }
}

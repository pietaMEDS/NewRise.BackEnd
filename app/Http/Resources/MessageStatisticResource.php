<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\Forum;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageStatisticResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'text'=>$this->text,
            'status' => $this->status,
            'creator'=>UserResource::make(User::find($this->user_id)),
            'forum'=>ForumResource::make(Forum::find($this->forum_id)),
            'reaction'=>MessageResource::make(Message::find($this->message_id)),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}

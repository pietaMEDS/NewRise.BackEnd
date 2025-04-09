<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'isPinned'=>$this->isPinned,
            'type'=>$this->type,
            'name' => $this->name,
            'text' => $this->text,
            'reactions' => null,
            'last_message' => MessageResource::make(Message::where([['forum_id', $this->forum_id],['status', '!=', 'deleted']])->orderBy('created_at', 'desc')->first()),
        ];
    }
}

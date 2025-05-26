<?php

namespace App\Http\Resources;

use App\Models\Forum;
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
            'image'=>$this->image,
            'name' => $this->name,
            'text' => $this->text,
            'reactions' => null,
            'forum' => ForumResource::make(Forum::find($this->forum_id)),
            'last_message' => MessageResource::make(Message::where('forum_id', $this->forum_id)->where(function ($query) {
        $query->whereNull('status')->orWhere('status', '!=', 'deleted');
    })->orderBy('created_at', 'desc')->first()),
        ];
    }
}

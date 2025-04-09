<?php

namespace App\Http\Resources;

use App\Models\Message;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ForumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'desc'=>$this->description,
//            'postsCount'=>Message::where('forum_id', $this->id)->where('status','!=','deleted')->count(),
            'posts_count'=>$this->posts_count,
            'creator'=>UserResource::make(User::find($this->user_id)),
            'theme'=>ThemeResource::make(Theme::find($this->theme_id)),
            'views'=>$this->views,
        ];
    }
}

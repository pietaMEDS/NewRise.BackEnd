<?php

namespace App\Http\Resources;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'desc'=>$this->description,
            "status"=>$this->status,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->updated_at,
            "postsCount"=>Forum::where('theme_id', $this->id)->count(),
        ];
    }
}

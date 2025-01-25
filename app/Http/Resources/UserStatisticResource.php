<?php

namespace App\Http\Resources;

use App\Models\achieve;
use App\Models\Achievement;
use App\Models\ProfileImage;
use App\Models\Rank;
use App\Models\Role;
use App\Models\RoleAccess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserStatisticResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "login" => $this->login,
            "role" => RoleResource::make(Role::find(RoleAccess::all()->firstWhere("user_id", '=', $this->id)->role_id)),
            "rank" => RankResource::make(Rank::find($this->rank_id)),
            "name" => $this->name,
            "avatar" => ProfileImageResource::make(ProfileImage::all()->firstWhere("user_id", '=', $this->id)),
            "achievements" => $this->achievements,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

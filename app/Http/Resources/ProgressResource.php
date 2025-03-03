<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "current_xp"=>$this->current_xp,
            "max_xp"=>$this->max_xp,
        ];
    }
}

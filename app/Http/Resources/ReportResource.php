<?php

namespace App\Http\Resources;


use App\Models\report_messages;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "admin_id"=>$this->admin_id,
            "user_id"=>$this->user_id,
            "theme"=>$this->theme,
            "text"=>$this->text,
            "status"=>$this->status,
            'messages' => MessageResource::collection(report_messages::where('report_id', $this->id)->get()),
        ];
    }
}

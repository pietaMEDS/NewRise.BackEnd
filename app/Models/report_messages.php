<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class report_messages extends Model
{
    protected $fillable = [
        'text',
        'report_id',
        'user_id',
        'status',
    ];
}

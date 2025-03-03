<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rank_progresses extends Model
{
    protected $fillable = [
        'user_id',
        'current_xp',
        'max_xp',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class progress_actions extends Model
{
    protected $fillable = [
        'name',
        'method',
        'xp'
    ];
}

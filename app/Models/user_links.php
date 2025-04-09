<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_links extends Model
{
    protected $fillable = [
        'user_id',
        'fingerprint_m',
        'fingerprint_c',
        'visitor_id',
    ];
}

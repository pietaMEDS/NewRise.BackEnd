<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $fillable = [
        'route',
        'fingerprint_m',
        'fingerprint_c',
        'path_to',
        'path_from',
        'useragent',
        'primary_account',
        'visitor_score',
        'visitor_id',
    ];
}

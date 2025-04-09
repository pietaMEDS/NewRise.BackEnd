<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTime extends Model
{
    protected $fillable = [
        'path',
        'fingerprint_c',
        'useragent',
        'time_spent'
    ];
}

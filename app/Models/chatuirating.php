<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class chatuirating extends Model
{
    protected $fillable = [
        'email',
        'name',
        'rate',
    ];
}

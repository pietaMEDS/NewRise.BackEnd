<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reports extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'theme',
        'text',
        'status',
        'created_at',
        'updated_at',
        'link',
    ];
}
